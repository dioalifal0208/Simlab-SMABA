<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\LoanStatusUpdated; // <-- TAMBAHKAN INI
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

        $loans = $query->paginate(15);

        return view('loans.index', compact('loans'));
    }

    /**
     * Menampilkan formulir untuk membuat pengajuan peminjaman baru.
     */
    public function create(Request $request)
    {
        $items = Item::where('kondisi', 'Baik')->orderBy('nama_alat')->get();

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

        return view('loans.create', compact('items', 'selectedItemIds'));
    }

    /**
     * Menyimpan pengajuan peminjaman baru ke database.
     */
    public function store(Request $request)
{
    $request->validate([
        'tanggal_pinjam' => 'required|date|after_or_equal:today',
        'tanggal_estimasi_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        'items' => 'required|array|min:1',
        'jumlah.*' => 'nullable|integer|min:1',
    ]);

    // ... (Logika validasi stok Anda yang sudah ada) ...
    // (Saya akan gunakan kode dari update terakhir kita)
    $stockErrors = [];
    $itemsToAttach = [];
    $requestedItems = Item::findMany($request->items);
    foreach ($request->items as $itemId) {
        $item = $requestedItems->find($itemId);
        if ($item && isset($request->jumlah[$itemId]) && $request->jumlah[$itemId] > 0) {
            $requestedQuantity = (int) $request->jumlah[$itemId];
            if ($item->jumlah < $requestedQuantity) {
                $stockErrors[] = "Stok '{$item->nama_alat}' tidak mencukupi (sisa: {$item->jumlah}, diminta: {$requestedQuantity}).";
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
    ]);

    $loan->items()->attach($itemsToAttach);

    // --- PENAMBAHAN: KIRIM NOTIFIKASI KE SEMUA ADMIN ---
    try {
        $admins = User::where('role', 'admin')->get(); // 1. Cari semua admin
        Notification::send($admins, new NewLoanRequest($loan)); // 2. Kirim notifikasi
    } catch (\Exception $e) {
        // Tangani jika pengiriman notifikasi gagal (misal: error setup)
        // Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
    }
    // ----------------------------------------------------

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
    public function update(Request $request, Loan $loan)
{
    Gate::authorize('is-admin');

    $request->validate([
        'status' => 'required|in:approved,rejected,completed',
        'admin_notes' => 'nullable|string|max:1000',
    ]);

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

    // --- PENAMBAHAN: KIRIM NOTIFIKASI KE PENGGUNA ---
    if ($request->status == 'approved' || $request->status == 'rejected') {
        // Kita perlu memuat relasi 'user' untuk mengirim notifikasi
        $loan->load('user'); 
        Notification::send($loan->user, new LoanStatusUpdated($loan));
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
