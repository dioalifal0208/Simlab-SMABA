<?php

namespace App\Http\Controllers;

use App\Models\ItemImage;
use App\Models\Item;
use App\Exports\ItemsExport;
use App\Exports\ItemsTemplateExport;
use App\Imports\ItemImport;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Requests\ImportItemRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar item dengan search, filter, dan sorting.
     */
    public function index(Request $request)
    {
        // Mengambil parameter dari URL untuk sorting, defaultnya created_at desc
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Memulai query
        $query = Item::with(['user', 'activeLoans']);

        // Filter otomatis untuk guru berdasarkan lab-nya
        if (auth()->user()->role === 'guru' && auth()->user()->laboratorium) {
            $lockedLab = auth()->user()->laboratorium;
            // Paksa filter di query untuk mencegah pengubahan manual di request
            $query->where('laboratorium', $lockedLab);
            $request->merge(['laboratorium' => $lockedLab]);
        } elseif (auth()->user()->role === 'guru' && !auth()->user()->laboratorium) {
            // Jika guru belum punya penugasan lab, tampilkan inventaris kosong dengan pesan
            $items = Item::whereRaw('1=0')->paginate(12);
            return view('items.index', compact('items'))
                ->withErrors(['laboratorium' => 'Akun Anda belum memiliki penugasan laboratorium. Hubungi admin.']);
        }

        // Menerapkan filter pencarian jika ada
        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        // Menerapkan filter kondisi jika ada
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Menerapkan filter tipe jika ada
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('laboratorium')) {
            $query->where('laboratorium', $request->laboratorium);
        }

        // Menjalankan query dengan sorting dan paginasi
        // withQueryString() penting agar filter tetap aktif saat pindah halaman
        $items = $query->orderBy($sort, $direction)->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return view('items.partials.item-table', compact('items'));
        }

        return view('items.index', compact('items'));
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
    public function store(StoreItemRequest $request)
    {
        // Authorization sudah di-handle di StoreItemRequest
        // Validation sudah di-handle di StoreItemRequest
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        // Buat item tanpa foto terlebih dahulu
        $item = Item::create($validated);

        // Jika ada file foto yang diunggah, proses dengan compression dan thumbnails
        if ($request->hasFile('photos')) {
            // Create ImageManager instance with GD driver
            $manager = new ImageManager(new Driver());
            
            foreach ($request->file('photos') as $photo) {
                // Generate unique filename
                $filename = uniqid() . '_' . time() . '.jpg';
                
                // Load image dengan Intervention v3
                $image = $manager->read($photo->getPathname());
                
                // Compress dan resize original (max 1200px width, maintain aspect ratio)
                $image->scaleDown(width: 1200);
                
                // Encode to JPEG with quality 80
                $original = $image->toJpeg(80);
                
                // Save compressed original
                Storage::disk('public')->put(
                    'item-photos/original/' . $filename,
                    (string) $original
                );
                
                // Generate small thumbnail (150x150 cover)
                $thumbnailSmall = $manager->read($photo->getPathname())
                    ->cover(150, 150)
                    ->toJpeg(85);
                Storage::disk('public')->put(
                    'item-photos/thumbnails/small/' . $filename,
                    (string) $thumbnailSmall
                );
                
                // Generate medium thumbnail (400x400 cover)
                $thumbnailMedium = $manager->read($photo->getPathname())
                    ->cover(400, 400)
                    ->toJpeg(85);
                Storage::disk('public')->put(
                    'item-photos/thumbnails/medium/' . $filename,
                    (string) $thumbnailMedium
                );
                
                // Store path in database (pointing to original)
                $item->images()->create([
                    'path' => 'item-photos/original/' . $filename
                ]);
            }
        }

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu item.
     */
    public function show($id)
    {
        // Gunakan findOrFail untuk otomatis menampilkan 404 jika item tidak ditemukan.
        $item = Item::with(['user', 'images', 'practicumModules.user', 'maintenanceLogs.user'])->findOrFail($id);

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
    public function update(UpdateItemRequest $request, Item $item)
    {
        // Authorization dan validation sudah di-handle di UpdateItemRequest
        $validated = $request->validated();

        // Update data item
        $item->update($validated);

        // Jika ada file foto baru yang diunggah, proses dengan compression dan thumbnails
        if ($request->hasFile('photos')) {
            // Create ImageManager instance with GD driver
            $manager = new ImageManager(new Driver());
            
            foreach ($request->file('photos') as $photo) {
                // Generate unique filename
                $filename = uniqid() . '_' . time() . '.jpg';
                
                // Load image dengan Intervention v3
                $image = $manager->read($photo->getPathname());
                
                // Compress dan resize original (max 1200px width, maintain aspect ratio)
                $image->scaleDown(width: 1200);
                
                // Encode to JPEG with quality 80
                $original = $image->toJpeg(80);
                
                // Save compressed original
                Storage::disk('public')->put(
                    'item-photos/original/' . $filename,
                    (string) $original
                );
                
                // Generate small thumbnail (150x150 cover)
                $thumbnailSmall = $manager->read($photo->getPathname())
                    ->cover(150, 150)
                    ->toJpeg(85);
                Storage::disk('public')->put(
                    'item-photos/thumbnails/small/' . $filename,
                    (string) $thumbnailSmall
                );
                
                // Generate medium thumbnail (400x400 cover)
                $thumbnailMedium = $manager->read($photo->getPathname())
                    ->cover(400, 400)
                    ->toJpeg(85);
                Storage::disk('public')->put(
                    'item-photos/thumbnails/medium/' . $filename,
                    (string) $thumbnailMedium
                );
                
                // Store path in database
                $item->images()->create([
                    'path' => 'item-photos/original/' . $filename
                ]);
            }
        }

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

        // Hapus semua gambar terkait dari storage
        foreach ($item->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        // Relasi diatur dengan onDelete('cascade'), jadi record di item_images akan terhapus otomatis
        // saat item dihapus.

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
            // 3. Ambil semua item beserta relasi images (sistem multi-foto)
            $items = Item::with('images')->whereIn('id', $itemIds)->get();

            // 4. Kumpulkan semua path foto dari relasi item_images dan hapus dari storage
            foreach ($items as $item) {
                foreach ($item->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            // 5. Hapus semua record dari database dalam satu query
            // (relasi item_images terhapus otomatis via onDelete cascade di migration)
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
     * Menangani unggahan file dan proses impor dalam satu langkah.
     * Mirip dengan alur kerja impor pengguna.
     */
    public function handleImport(ImportItemRequest $request)
    {
        // Authorization dan validation sudah di-handle di ImportItemRequest

        try {
            // Buat instance ItemImport terlebih dahulu agar bisa diakses setelah import
            $import = new ItemImport;
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $failures      = $import->failures();
            $failureCount  = $failures->count();

            // Jika tidak ada satu pun baris yang berhasil, anggap gagal
            if ($importedCount === 0 && $failureCount > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors())
                        . " (Nilai: '" . ($failure->values()[$failure->attribute()] ?? '-') . "')";
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Import gagal. Semua baris tidak lolos validasi. Pastikan data sudah sesuai dengan ketentuan (Tipe: Alat/Bahan, Lab: Biologi/Fisika/Bahasa, Kondisi: Baik/Kurang Baik/Rusak).',
                    'errors'  => $errorMessages,
                ], 422);
            }

            // Jika ada yang berhasil tapi ada juga yang gagal
            $message = "{$importedCount} baris berhasil disimpan.";
            if ($failureCount > 0) {
                $message .= " {$failureCount} baris di-skip karena tidak lolos validasi.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (ValidationException $e) {
            // Tangkap error validasi dari file Excel
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris " . $failure->row() . ": " . implode(', ', $failure->errors()) . " (Nilai: '" . $failure->values()[$failure->attribute()] . "')";
            }
            // Kembalikan error sebagai JSON
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Harap periksa baris berikut di file Anda:',
                'errors' => $errorMessages
            ], 422); // 422 Unprocessable Entity

        } catch (\Exception $e) {
            // Tangkap error umum lainnya (misal: format file korup)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menangani permintaan untuk mengekspor data item ke file Excel.
     */
    public function handleExport()
    {
        // 2. Otorisasi, pastikan hanya admin
        $this->authorize('is-admin');

        // 3. Panggil fungsi download dari Maatwebsite/Excel
        //    Parameter pertama adalah instance dari class export kita
        //    Parameter kedua adalah nama file yang akan diunduh
        return Excel::download(new ItemsExport, 'data_inventaris_lab-smaba_'.date('Y-m-d').'.xlsx');
    }

    /**
     * Menghasilkan dan mengunduh file template Excel untuk impor item.
     */
    public function exportTemplate()
    {
        $this->authorize('is-admin');
        return Excel::download(new ItemsTemplateExport, 'template_import_item.xlsx');
    }
}
