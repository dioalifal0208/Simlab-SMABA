<?php

namespace App\Http\Controllers;

use App\Models\Item; // <-- Import Item
use App\Models\PracticumModule; // <-- Import PracticumModule
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PracticumModuleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar semua modul praktikum.
     */
    public function index()
    {
        // Ambil semua modul, beserta data pembuatnya, diurutkan dari terbaru
        $modules = PracticumModule::with('user')->latest()->paginate(10);
        
        return view('practicum-modules.index', compact('modules'));
    }

    /**
     * Menampilkan form untuk membuat modul praktikum baru.
     */
    public function create()
    {
        // Kita butuh daftar semua item untuk ditampilkan di form
        $items = Item::orderBy('nama_alat')->get(); 
        
        return view('practicum-modules.create', compact('items'));
    }

    /**
     * Menyimpan modul praktikum baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'items' => 'nullable|array', // Pastikan 'items' adalah array
            'items.*' => 'exists:items,id', // Pastikan setiap item ID ada di tabel items
        ]);

        $documentPath = null;
        $originalFilename = null;

        // Proses upload dokumen jika ada
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $originalFilename = $file->getClientOriginalName();
            // Simpan ke storage/app/public/practicum_modules
            $documentPath = $file->store('practicum_modules', 'public');
        }

        // 2. Buat modul baru
        $module = PracticumModule::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'document_path' => $documentPath,
            'original_filename' => $originalFilename,
            'user_id' => Auth::id(),
        ]);

        // 3. Lampirkan (attach/sync) item-item yang dipilih ke modul
        // Method sync() sangat cocok untuk relasi many-to-many
        if (isset($validated['items'])) {
            $module->items()->sync($validated['items']);
        } else {
            // Jika tidak ada item yang dipilih, pastikan relasinya kosong
            $module->items()->sync([]); 
        }

        // 4. Redirect ke halaman daftar modul dengan pesan sukses
        return redirect()->route('practicum-modules.index')
               ->with('success', 'Modul praktikum berhasil dibuat.');
    }

    /**
     * Menampilkan detail satu modul praktikum.
     */
    public function show($id)
    {
        // Gunakan findOrFail dan eager load relasi 'user' (pembuat) dan 'items' (alat/bahan)
        $module = PracticumModule::with(['user', 'items'])->findOrFail($id);
        return view('practicum-modules.show', compact('module'));
    }

    /**
     * Menampilkan form untuk mengedit modul. (Akan kita isi nanti)
     */
    public function edit(PracticumModule $practicumModule)
    {
        // Otorisasi, misal hanya admin/guru
        $this->authorize('manage-documents'); // Menggunakan gate yang sama dengan Pustaka Digital

        // Ambil semua item untuk ditampilkan di checklist
        $items = Item::orderBy('nama_alat')->get();
        
        // Ambil ID dari item-item yang SUDAH terhubung dengan modul ini
        $selectedItems = $practicumModule->items()->pluck('items.id')->toArray();

        return view('practicum-modules.edit', [
            'module' => $practicumModule,
            'items' => $items,
            'selectedItems' => $selectedItems,
        ]);
    }

    /**
     * Mengupdate modul di database. (Akan kita isi nanti)
     */
    public function update(Request $request, PracticumModule $practicumModule)
    {
        // Otorisasi
        $this->authorize('manage-documents');

        // 1. Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
            'items' => 'nullable|array', // Pastikan 'items' adalah array
            'items.*' => 'exists:items,id', // Pastikan setiap item ID ada di tabel items
        ]);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
        ];

        // Proses upload dokumen jika ada
        if ($request->hasFile('document')) {
            // Hapus dokumen lama jika ada
            if ($practicumModule->document_path && Storage::disk('public')->exists($practicumModule->document_path)) {
                Storage::disk('public')->delete($practicumModule->document_path);
            }

            $file = $request->file('document');
            $updateData['original_filename'] = $file->getClientOriginalName();
            $updateData['document_path'] = $file->store('practicum_modules', 'public');
        } elseif ($request->has('remove_document') && $request->remove_document == '1') {
            // Jika user memilih untuk menghapus dokumen
            if ($practicumModule->document_path && Storage::disk('public')->exists($practicumModule->document_path)) {
                Storage::disk('public')->delete($practicumModule->document_path);
            }
            $updateData['document_path'] = null;
            $updateData['original_filename'] = null;
        }

        // 2. Update data modul
        $practicumModule->update($updateData);

        // 3. Sinkronkan (sync) item-item yang dipilih
        if (isset($validated['items'])) {
            $practicumModule->items()->sync($validated['items']);
        } else {
            // Jika tidak ada item yang dipilih, hapus semua relasi
            $practicumModule->items()->sync([]); 
        }

        // 4. Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('practicum-modules.index')
               ->with('success', 'Modul praktikum berhasil diperbarui.');
    }

    /**
     * Menghapus modul dari database. (Akan kita isi nanti)
     */
    /**
     * Menghapus modul dari database.
     */
    public function destroy(PracticumModule $practicumModule)
    {
        // Otorisasi: Pastikan hanya user yang berwenang (misal admin/guru)
        $this->authorize('manage-documents'); // Sesuaikan gate jika perlu

        // Hapus file dokumen jika ada
        if ($practicumModule->document_path && Storage::disk('public')->exists($practicumModule->document_path)) {
            Storage::disk('public')->delete($practicumModule->document_path);
        }

        // Hapus modul
        $practicumModule->delete();

        return redirect()->route('practicum-modules.index')
               ->with('success', 'Modul praktikum berhasil dihapus.');
    }

    /**
     * Mengunduh dokumen pendukung modul.
     */
    public function downloadDocument(PracticumModule $practicumModule)
    {
        if (!$practicumModule->document_path || !Storage::disk('public')->exists($practicumModule->document_path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($practicumModule->document_path);
        $filename = $practicumModule->original_filename ?? basename($path);
        
        return response()->download($path, $filename);
    }
}