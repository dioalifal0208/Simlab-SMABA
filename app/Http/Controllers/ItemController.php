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

class ItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar item dengan search, filter, dan sorting.
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        // PERBAIKAN: Tambahkan Eager Loading 'user'
        $query = Item::with('user');

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->input('kondisi'));
        }
        
        // PERBAIKAN: Tambahkan filter untuk 'tipe'
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->input('tipe'));
        }

        $items = $query->orderBy($sort, $direction)->paginate(12); // Menaikkan paginasi

        return view('items.index', compact('items', 'sort', 'direction'));
    }

    /**
     * Menampilkan form untuk membuat item baru.
     */
    public function create()
    {
        $this->authorize('is-admin');
        return view('items.create');
    }

    /**
     * Menyimpan item baru ke database, termasuk foto.
     */
    public function store(Request $request)
    {
        $this->authorize('is-admin');

        // PERBAIKAN: Menambahkan validasi untuk field baru
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai', // Validasi untuk Tipe
            'jumlah' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0', // Validasi untuk Stok Minimum
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string', // Mengganti 'keterangan' menjadi 'deskripsi'
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('item_photos', 'public');
            $validated['photo'] = $path;
        }

        // Tambahkan user_id
        $validated['user_id'] = $request->user()->id;

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu item.
     */
    public function show(Item $item)
    {
        // Kode Anda di sini sudah optimal, memuat relasi yang diperlukan.
        $item->load(['user', 'practicumModules.user', 'maintenanceLogs.user']); // Menambahkan maintenanceLogs.user
        
        return view('items.show', compact('item'));
    }

    /**
     * Menampilkan form untuk mengedit item.
     */
    public function edit(Item $item)
    {
        $this->authorize('is-admin');
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data item di database, termasuk foto.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('is-admin');

        // PERBAIKAN: Menambahkan validasi untuk field baru
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai', // Validasi untuk Tipe
            'jumlah' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0', // Validasi untuk Stok Minimum
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string', // Mengganti 'keterangan' menjadi 'deskripsi'
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $path = $request->file('photo')->store('item_photos', 'public');
            $validated['photo'] = $path;
        }
        
        // PERBAIKAN: Menggunakan $validated untuk update
        // Ini adalah perbaikan utama untuk masalah Anda
        $item->update($validated);

        return redirect()->route('items.show', $item->id)->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Menghapus item dari database, termasuk fotonya.
     */
    public function destroy(Item $item)
    {
        $this->authorize('is-admin');

        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
/**
 * Menangani file upload dan menjalankan impor.
 */
public function handleImport(Request $request)
    {
        $this->authorize('is-admin');
        
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        try {
            // Jalankan impor
            Excel::import(new ItemImport, $request->file('file'));
        
        } catch (ValidationException $e) {
            // Tangkap error validasi dari file Excel
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . $failure->errors()[0];
            }
            // Kembalikan error sebagai JSON
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa baris berikut:',
                'errors' => $errorMessages
            ], 422);
        
        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage()
            ], 500);
        }

        // Kembalikan pesan sukses sebagai JSON
        return response()->json([
            'success' => true,
            'message' => 'Data item berhasil diimpor.'
        ]);
    }
}