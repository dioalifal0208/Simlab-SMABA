<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\BookingStatusUpdated;
use App\Notifications\NewBookingRequest;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking lab dengan filter.
     */
    public function index(Request $request)
    {
        // Memulai query dasar dengan eager loading relasi 'user' dan diurutkan dari yang terbaru.
        $query = Booking::with('user')->latest();

        // Jika pengguna bukan admin, hanya tampilkan booking miliknya.
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Kunci laboratorium sesuai penugasan guru
        if (Auth::user()->role === 'guru' && Auth::user()->laboratorium) {
            $request->merge(['laboratorium' => Auth::user()->laboratorium]);
        }

        // Menerapkan filter status jika ada dari request URL.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('laboratorium')) {
            $query->where('laboratorium', $request->laboratorium);
        }

        // Eksekusi query dengan paginasi.
        $bookings = $query->paginate(15);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan form untuk membuat booking baru.
     */
    public function create()
    {
        $selectedLaboratorium = Auth::user()->role === 'admin'
            ? null
            : (Auth::user()->laboratorium ?? 'Biologi');

        return view('bookings.create', compact('selectedLaboratorium'));
    }

    /**
     * Menyimpan booking baru ke database.
     */
    public function store(StoreBookingRequest $request)
    {
        // Authorization dan validation sudah di-handle di StoreBookingRequest
        $validated = $request->validated();

        // Jika guru, paksa gunakan lab yang ditugaskan
        if (Auth::user()->role === 'guru') {
            if (!Auth::user()->laboratorium) {
                return back()->withErrors(['laboratorium' => 'Akun Anda belum memiliki penugasan laboratorium. Hubungi admin.'])->withInput();
            }
            if ($validated['laboratorium'] !== Auth::user()->laboratorium) {
                return back()->withErrors(['laboratorium' => 'Anda hanya dapat mengajukan untuk Lab ' . Auth::user()->laboratorium . '.'])->withInput();
            }
        }

        $selectedLab = Auth::user()->role === 'admin'
            ? $validated['laboratorium']
            : Auth::user()->laboratorium;

        $isConflict = Booking::where('status', 'approved')
            ->where('laboratorium', $selectedLab)
            ->where(function ($query) use ($validated) {
                $query->where('waktu_mulai', '<', $validated['waktu_selesai'])
                      ->where('waktu_selesai', '>', $validated['waktu_mulai']);
            })
            ->exists();

        if ($isConflict) {
            return back()->withErrors([
                'waktu_mulai' => 'Jadwal yang Anda pilih bentrok dengan booking lain yang sudah disetujui. Silakan pilih waktu yang berbeda.'
            ])->withInput();
        }

        // --- UPDATE PROFILE USER ON-THE-FLY ---
        // Jika user mengisi data diri di form booking, kita update profile mereka
        $user = $request->user();
        if ($request->hasAny(['nomor_induk', 'kelas', 'phone_number'])) {
            $user->update([
                'nomor_induk' => $request->nomor_induk ?? $user->nomor_induk,
                'kelas' => $request->kelas ?? $user->kelas,
                'phone_number' => $request->phone_number ?? $user->phone_number,
            ]);
        }
        // ---------------------------------------

        $booking = Booking::create([
            'user_id' => $user->id,
            'guru_pengampu' => $validated['guru_pengampu'],
            'tujuan_kegiatan' => $validated['tujuan_kegiatan'],
            'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
            'status' => 'pending',
            'laboratorium' => $selectedLab,
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'jumlah_peserta' => $validated['jumlah_peserta'],
        ]);

        // Kirim notifikasi ke semua admin bahwa ada booking lab baru
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewBookingRequest($booking));
            }
        } catch (\Exception $e) {
            // Optional: log error jika diperlukan
            // \Log::error('Gagal mengirim notifikasi booking baru: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Pengajuan booking lab berhasil dikirim dan sedang menunggu persetujuan.');
    }

    /**
     * Menampilkan detail dari satu booking.
     */
    // app/Http/Controllers/BookingController.php

public function show($id)
{
    $booking = Booking::findOrFail($id);

    // PERBAIKAN: Ubah kedua ID menjadi (int) sebelum membandingkan
    if (Auth::user()->role !== 'admin' && (int) $booking->user_id !== (int) Auth::id()) {
        abort(403);
    }
    $booking->load('user');
    return view('bookings.show', compact('booking'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Mengupdate status booking (aksi oleh Admin).
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
{
    // Authorization dan validation sudah di-handle di UpdateBookingRequest
    $validated = $request->validated();

    if ($request->status == 'approved') {
        $isConflict = Booking::where('status', 'approved')
            ->where('id', '!=', $booking->id)
            ->where('laboratorium', $booking->laboratorium)
            ->where(function ($query) use ($booking) {
                $query->where('waktu_mulai', '<', $booking->waktu_selesai)
                      ->where('waktu_selesai', '>', $booking->waktu_mulai);
            })
            ->exists();

        if ($isConflict) {
            return back()->withErrors(['status' => 'Gagal menyetujui. Jadwal ini bentrok dengan booking lain yang sudah disetujui.']);
        }
    }

    $data = ['status' => $request->status];
    if ($request->has('admin_notes')) {
        $data['admin_notes'] = $request->admin_notes;
    }

    $booking->update($data);

    // --- PENAMBAHAN: KIRIM NOTIFIKASI KE PENGGUNA ---
    if ($request->status == 'approved' || $request->status == 'rejected') {
        try {
            // Kita perlu memuat relasi 'user' untuk mengirim notifikasi
            $booking->load('user'); 
            Notification::send($booking->user, new BookingStatusUpdated($booking));
        } catch (\Exception $e) {
            // Opsional: Log error untuk debugging di masa depan
            // \Log::error('Gagal mengirim notifikasi update status booking: ' . $e->getMessage());
        }
    }
    // ----------------------------------------------

    return redirect()->route('bookings.show', $booking->id)->with('success', 'Status booking berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
{
    // Otorisasi: Hanya admin yang boleh menghapus
    Gate::authorize('is-admin');

    // Opsional: Logika tambahan (misal, hanya boleh hapus jika pending/rejected)
    // if (!in_array($booking->status, ['pending', 'rejected'])) {
    //     return back()->withErrors(['message' => 'Hanya booking yang pending atau ditolak yang bisa dihapus.']);
    // }

    // Hapus notifikasi yang terkait dengan booking ini sebelum menghapus booking itu sendiri
    $booking->notifications()->delete();

    // Hapus data booking
    $booking->delete();

    // ... (existing destroy method)
        // Redirect ke halaman daftar booking
        return redirect()->route('bookings.index')
               ->with('success', 'Data booking berhasil dihapus.');
    }

    /**
     * Menampilkan Surat Peminjaman Lab untuk dicetak.
     */
    public function printSurat(Booking $booking)
    {
        // Otorisasi: Hanya Admin atau Pemilik Booking yang boleh mencetak
        if (Auth::user()->role !== 'admin' && Auth::id() !== $booking->user_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencetak surat ini.');
        }

        // Pastikan hanya booking yang disetujui atau selesai yang bisa dicetak
        if ($booking->status !== 'approved' && $booking->status !== 'completed') {
            return back()->withErrors(['message' => 'Hanya peminjaman yang sudah disetujui yang dapat dicetak suratnya.']);
        }

        return view('bookings.surat', compact('booking'));
    }

    /**
     * Menyimpan detail pengembalian (Lab Return Report).
     */
    public function storeReturnDetails(Request $request, Booking $booking)
    {
        // Validasi input
        $validated = $request->validate([
            'kondisi' => 'required|array',
            'kondisi.*' => 'string',
        ]);

        // Cek otorisasi: hanya peminjam atau admin
        if (Auth::user()->role !== 'admin' && Auth::id() !== $booking->user_id) {
            abort(403);
        }

        $booking->update([
            'waktu_pengembalian' => now(),
            'kondisi_lab' => $validated['kondisi'],
        ]);

        return back()->with('success', 'Laporan pengembalian berhasil disimpan.');
    }

    /**
     * Verifikasi Booking via QR Code (Public Access).
     */
    public function verify(Booking $booking)
    {
        // Eager load data user untuk menampilkan nama peminjam
        $booking->load('user');

        return view('bookings.verify', compact('booking'));
    }
}
