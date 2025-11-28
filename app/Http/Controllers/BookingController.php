<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // <-- Direkomendasikan untuk otorisasi
use App\Notifications\BookingStatusUpdated; // <-- TAMBAHKAN INI
use App\Notifications\NewBookingRequest;
use Illuminate\Support\Facades\Notification; // <-- TAMBAHKAN INI

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_pengampu' => 'required|string|max:255',
            'tujuan_kegiatan' => 'required|string',
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'jumlah_peserta' => 'nullable|integer|min:1',
        ]);

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

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'guru_pengampu' => $validated['guru_pengampu'],
            'tujuan_kegiatan' => $validated['tujuan_kegiatan'],
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
    public function update(Request $request, Booking $booking)
{
    Gate::authorize('is-admin');

    $request->validate([
        'status' => 'required|in:approved,rejected,completed',
        'admin_notes' => 'nullable|string|max:1000',
    ]);

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

    $booking->update([
        'status' => $request->status,
        'admin_notes' => $request->admin_notes,
    ]);

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

    // Redirect ke halaman daftar booking
    return redirect()->route('bookings.index')
           ->with('success', 'Data booking berhasil dihapus.');
}
}
