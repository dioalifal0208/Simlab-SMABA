<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log; // Tambahkan Log untuk debugging

class ItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar item dengan search, filter, dan sorting.
     */
    public function index(Request $request)
    {
        // Semua logika sekarang ada di App\Http\Livewire\ItemIndex.php
        // Method ini hanya perlu me-return view yang memuat komponen Livewire.
        return view('items.index');
    }

    /**
     * Menampilkan form untuk membuat item baru.
     */
    public function create()
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');
        return view('items.create');
    }

    /**
     * Menyimpan item baru ke database, termasuk foto.
     */
    public function store(Request $request)
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai',
            'jumlah' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('item_photos', 'public');
            $validated['photo'] = $path;
        }
        $validated['user_id'] = $request->user()->id;
        Item::create($validated);
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu item.
     */
    public function show($id)
    {
        // Gunakan findOrFail untuk otomatis menampilkan 404 jika item tidak ditemukan.
        $item = Item::with(['user', 'practicumModules.user', 'maintenanceLogs.user'])->findOrFail($id);

        return view('items.show', compact('item'));
    }

    /**
     * Menampilkan form untuk mengedit item.
     */
    public function edit(Item $item)
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data item di database, termasuk foto.
     */
    public function update(Request $request, Item $item)
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai',
            'jumlah' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $path = $request->file('photo')->store('item_photos', 'public');
            $validated['photo'] = $path;
        }
        $item->update($validated);
        return redirect()->route('items.show', $item->id)->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Menghapus item dari database, termasuk fotonya.
     */
    public function destroy(Item $item)
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');

        // REKOMENDASI: Tambahkan pengecekan sebelum menghapus
        if ($item->loans()->whereIn('status', ['approved', 'Terlambat'])->exists()) {
            return redirect()->route('items.index')
                ->with('error', 'Item "' . $item->nama_alat . '" tidak dapat dihapus karena sedang dalam proses peminjaman aktif.');
        }

        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }

    // ==============================================
    // ## METHOD BARU UNTUK HAPUS MASSAL ##
    // ==============================================
    public function deleteMultiple(Request $request)
    {
        // 1. Otorisasi (sama seperti method destroy)
        $this->authorize('is-admin');

        // 2. Validasi input
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id', // Pastikan semua ID ada di tabel items
        ]);

        $itemIds = $request->input('item_ids');

        try {
            // 3. Ambil semua item yang akan dihapus
            $items = Item::whereIn('id', $itemIds)->get();

            // 4. Kumpulkan semua path foto yang valid (bukan null)
            // Method filter() akan menghapus nilai null/kosong
            $photoPaths = $items->pluck('photo')->filter()->all();

            // 5. Hapus semua foto dari storage dalam satu perintah
            if (!empty($photoPaths)) {
                Storage::disk('public')->delete($photoPaths);
            }

            // 6. Hapus semua record dari database dalam satu query
            Item::whereIn('id', $itemIds)->delete();
            
            Log::info('Bulk delete success for admin. IDs: ' . implode(', ', $itemIds));

            return redirect()->route('items.index')
                ->with('success', count($itemIds) . ' item berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error during bulk delete for admin: ' . $e->getMessage());
            return redirect()->route('items.index')
                ->with('error', 'Terjadi kesalahan saat mencoba menghapus item.');
        }
    }
    // ==============================================
    // ## AKHIR METHOD BARU ##
    // ==============================================

    /**
    * Menangani file upload dan menjalankan impor.
    */
    public function handleImport(Request $request)
    {
        // ... (Tidak ada perubahan)
        $this->authorize('is-admin');
        $request->validate(['file' => 'required|mimes:csv,xlsx,xls']);
        try {
            Excel::import(new ItemImport, $request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . $failure->errors()[0];
            }
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa baris berikut:',
                'errors' => $errorMessages
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data item berhasil diimpor.'
        ]);
    }
}