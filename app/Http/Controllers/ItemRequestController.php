<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class ItemRequestController extends Controller
{
    /**
     * Form pengajuan permintaan tambah alat/bahan (guru).
     */
    public function create()
    {
        return view('item-requests.create');
    }

    /**
     * Simpan permintaan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa',
            'urgensi' => 'required|in:normal,mendesak',
            'deskripsi' => 'nullable|string',
            'alasan_urgent' => 'nullable|string',
        ]);

        $laboratorium = Auth::user()->role === 'admin'
            ? $validated['laboratorium']
            : Auth::user()->laboratorium;

        if (Auth::user()->role === 'guru' && !$laboratorium) {
            return back()->withErrors(['laboratorium' => 'Akun Anda belum memiliki penugasan laboratorium. Hubungi admin.'])->withInput();
        }

        ItemRequest::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            ...$validated,
            'laboratorium' => $laboratorium,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Permintaan penambahan item terkirim. Admin akan meninjaunya.');
    }

    /**
     * Daftar permintaan (admin).
     */
    public function index()
    {
        Gate::authorize('is-admin');

        $requests = ItemRequest::with(['user', 'processor'])
            ->latest()
            ->paginate(12);

        return view('admin.item-requests.index', compact('requests'));
    }

    /**
     * Detail permintaan (admin).
     */
    public function show(ItemRequest $itemRequest)
    {
        Gate::authorize('is-admin');

        $itemRequest->load(['user', 'processor']);

        return view('admin.item-requests.show', compact('itemRequest'));
    }

    /**
     * Setujui permintaan dan buat item.
     */
    public function approve(Request $request, ItemRequest $itemRequest)
    {
        Gate::authorize('is-admin');

        if ($itemRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses.');
        }

        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'stok_minimum' => 'nullable|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:100',
            'admin_note' => 'nullable|string',
        ]);

        $itemData = [
            'nama_alat' => $itemRequest->nama_alat,
            'jumlah' => $validated['jumlah'],
            'stok_minimum' => $validated['stok_minimum'] ?? 0,
            'satuan' => $validated['satuan'],
            'kondisi' => $validated['kondisi'],
            'lokasi_penyimpanan' => $validated['lokasi_penyimpanan'],
            'deskripsi' => $itemRequest->deskripsi,
            'user_id' => Auth::id(),
            'laboratorium' => $itemRequest->laboratorium,
        ];

        if (Schema::hasColumn('items', 'tipe')) {
            $itemData['tipe'] = $validated['tipe'] ?? 'Alat';
        }

        Item::create($itemData);

        $itemRequest->update([
            'status' => 'approved',
            'admin_note' => $validated['admin_note'] ?? null,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()
            ->route('admin.item-requests.index')
            ->with('success', 'Permintaan disetujui dan item telah dibuat.');
    }

    /**
     * Tolak permintaan.
     */
    public function reject(Request $request, ItemRequest $itemRequest)
    {
        Gate::authorize('is-admin');

        if ($itemRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses.');
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|min:5',
        ]);

        $itemRequest->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()
            ->route('admin.item-requests.index')
            ->with('success', 'Permintaan ditolak.');
    }
}
