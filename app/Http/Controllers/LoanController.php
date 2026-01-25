<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use App\Services\FonnteService;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Mail\LoanApproved;
use App\Mail\LoanRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Notifications\LoanStatusUpdated;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewLoanRequest;
use Illuminate\Support\Str;


class LoanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'items'])->latest();

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if (Auth::user()->role === 'guru' && Auth::user()->laboratorium) {
            $request->merge(['laboratorium' => Auth::user()->laboratorium]);
        }
        if ($request->filled('laboratorium')) {
            $query->where('laboratorium', $request->laboratorium);
        }

        $loans = $query->paginate(15);

        return view('loans.index', compact('loans'));
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan peminjaman baru.
     */
    public function create(Request $request)
    {
        $selectedLaboratorium = $request->get('laboratorium', Auth::user()->laboratorium ?? 'Biologi');
        $items = Item::where('kondisi', 'Baik')
            ->where('laboratorium', $selectedLaboratorium)
            ->orderBy('nama_alat')
            ->get();

        $selectedItemIds = [];
        if ($request->filled('module_items')) {
            $selectedItemIds = Str::of((string) $request->input('module_items'))
                ->explode(',')
                ->map(function ($idStr) {
                    return (int) trim((string) $idStr);
                })
                ->filter(function ($id) {
                    return $id > 0;
                })
                ->values()
                ->all();
        }

        return view('loans.create', compact('items', 'selectedItemIds', 'selectedLaboratorium'));
    }

    /**
     * Menyimpan pengajuan peminjaman baru ke database.
     */
    public function store(StoreLoanRequest $request)
{
    // Authorization dan validation sudah di-handle di StoreLoanRequest
    $validated = $request->validated();

    // ... (Logika validasi stok Anda yang sudah ada) ...
    // (Saya akan gunakan kode dari update terakhir kita)
    $stockErrors = [];
    $itemsToAttach = [];
    $requestedItems = Item::findMany($request->items);
    $laboratoriumDipilih = $request->laboratorium;
    foreach ($request->items as $itemId) {
        $item = $requestedItems->find($itemId);
        if ($item && isset($request->jumlah[$itemId]) && $request->jumlah[$itemId] > 0) {
            $requestedQuantity = (int) $request->jumlah[$itemId];
            if ($item->jumlah < $requestedQuantity) {
                $stockErrors[] = "Stok '{$item->nama_alat}' tidak mencukupi (sisa: {$item->jumlah}, diminta: {$requestedQuantity}).";
            } elseif ($item->laboratorium !== $laboratoriumDipilih) {
                $stockErrors[] = "Item '{$item->nama_alat}' berada di lab {$item->laboratorium}, tidak sesuai pilihan lab ({$laboratoriumDipilih}).";
            } else {
                $itemsToAttach[$itemId] = ['jumlah' => $requestedQuantity];
            }
        }
    }
    if (!empty($stockErrors)) {
        return back()->withErrors($stockErrors)->withInput();
    }
    if (empty($itemsToAttach)) {
        return back()->withErrors(['items' => 'Harap masukkan jumlah (minimal 1) untuk setidaknya satu item.'])->withInput();
    }
    // --- Akhir Validasi Stok ---


    $loan = $request->user()->loans()->create([
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_estimasi_kembali' => $request->tanggal_estimasi_kembali,
        'status' => 'pending',
        'catatan' => $request->catatan,
        'laboratorium' => $laboratoriumDipilih,
    ]);

    $loan->items()->attach($itemsToAttach);

    // --- PENAMBAHAN: KIRIM NOTIFIKASI APLIKASI & WHATSAPP KE ADMIN ---
    try {
        // Notifikasi dalam aplikasi (ikon lonceng)
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewLoanRequest($loan));

        // Notifikasi WhatsApp via Fonnte (contoh 1 event)
        $adminNumbersEnv = (string) config('services.fonnte.admin_numbers', '');
        if ($adminNumbersEnv !== '') {
            $targets = collect(explode(',', $adminNumbersEnv))
                ->map(function ($number) {
                    return trim((string) $number);
                })
                ->filter()
                ->values()
                ->all();

            if (! empty($targets)) {
                $loan->loadMissing(['user', 'items']);

                $borrowerName = $loan->user->name ?? 'Seorang pengguna';
                $tanggalPinjam = $loan->tanggal_pinjam
                    ? $loan->tanggal_pinjam->format('d-m-Y')
                    : '-';

                $itemNames = $loan->items->pluck('nama_alat')->filter()->implode(', ');

                $message = "Pengajuan peminjaman baru.\n"
                    . "Peminjam : {$borrowerName}\n"
                    . "Tanggal : {$tanggalPinjam}\n"
                    . "Item    : " . ($itemNames !== '' ? $itemNames : '-')."\n"
                    . "Silakan cek aplikasi LAB-SMABA untuk detail lebih lanjut.";

                $fonnte = app(FonnteService::class);
                foreach ($targets as $target) {
                    $fonnte->sendMessage($target, $message);
                }
            }
        }
    } catch (\Exception $e) {
        // Jika pengiriman notifikasi gagal, jangan ganggu alur utama
        // Anda bisa menambahkan log jika ingin men-debug di kemudian hari.
        // \Log::error('Gagal mengirim notifikasi peminjaman baru: ' . $e->getMessage());
    }
    // -----------------------------------------------------------------

    return redirect()->route('dashboard')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
}

    /**
     * Menampilkan detail dari satu transaksi peminjaman.
     */
    // app/Http/Controllers/LoanController.php

public function show(Loan $loan)
{
    // PERBAIKAN: Ubah kedua ID menjadi (int) sebelum membandingkan
    if (Auth::user()->role !== 'admin' && (int) $loan->user_id !== (int) Auth::id()) {
        abort(403);
    }

    $loan->load(['user', 'items']);
    return view('loans.show', compact('loan'));
}

    /**
     * Menampilkan form untuk mengedit peminjaman.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Memproses perubahan status peminjaman (aksi oleh Admin).
     */
    public function update(UpdateLoanRequest $request, Loan $loan)
{
    // Authorization dan validation sudah di-handle di UpdateLoanRequest
    $validated = $request->validated();

    $loan->load('items'); // Load item untuk validasi stok

    if ($request->status == 'approved') {
        foreach ($loan->items as $item) {
            $jumlahDipinjam = $item->pivot->jumlah;
            if ($item->jumlah < $jumlahDipinjam) {
                return back()->withErrors(['status' => 'Gagal menyetujui. Stok ' . $item->nama_alat . ' tidak cukup.']);
            }
        }
    }

    $loan->status = $request->status;
    $loan->admin_notes = $request->admin_notes;

    if ($request->status == 'completed') {
        $loan->tanggal_kembali = now();
    }

    $loan->save(); // Simpan perubahan status

    // --- PENAMBAHAN: KIRIM NOTIFIKASI & EMAIL KE PENGGUNA ---
    if ($request->status == 'approved' || $request->status == 'rejected') {
        try {
            // Load relasi user untuk notifikasi
            $loan->load('user'); 
            
            // Send email notification
            if ($request->status == 'approved') {
                Mail::to($loan->user->email)->send(new LoanApproved($loan));
            } elseif ($request->status == 'rejected') {
                Mail::to($loan->user->email)->send(new LoanRejected($loan));
            }
            
            // Send in-app notification (existing)
            Notification::send($loan->user, new LoanStatusUpdated($loan));
        } catch (\Exception $e) {
            // Log error tapi tetap lanjut - email notification opsional
            \Log::warning('Email notification failed: ' . $e->getMessage());
        }
    }
    // ----------------------------------------------

    return redirect()->route('loans.show', $loan->id)->with('success', 'Status peminjaman diperbarui.');
}

    /**
     * Menghapus data peminjaman.
     */
    public function destroy(Loan $loan)
    {
        Gate::authorize('is-admin');

        // Hapus notifikasi yang terkait dengan loan ini sebelum menghapus loan itu sendiri
        $loan->notifications()->delete();

        $loan->items()->detach();
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
