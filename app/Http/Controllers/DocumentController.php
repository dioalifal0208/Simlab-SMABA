<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- PENTING
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar dokumen dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $query = Document::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // PERBAIKAN: Ganti ->get() atau ->all() menjadi ->paginate()
        $documents = $query->paginate(12); // Menampilkan 12 dokumen per halaman

        return view('documents.index', compact('documents'));
    }

    /**
     * Menyimpan dokumen yang baru diunggah.
     */
    public function store(Request $request)
{
    // 1. Validasi request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000', // Pastikan validasi ini ada
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480', // 20MB
    ]);

    try {
        // 2. Simpan file dan dapatkan data
        $file = $request->file('file');
        $path = $file->store('documents', 'public');
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize(); // <-- Ambil file_size

        // 3. Buat record di database
        Document::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'), // <-- Ambil description
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $fileType,
            'file_size' => $fileSize, // <-- Simpan file_size
            'user_id' => Auth::id(),
        ]);

    } catch (\Exception $e) {
        // 4. Tangkap error dan kirim sebagai 'session(error)'
        // (Ini akan ditampilkan oleh kode Blade yang baru Anda tambahkan)
        return redirect()->back()
            ->with('error', 'Gagal mengupload: ' . $e->getMessage())
            ->withInput(); // Bawa kembali input lama (title, desc) ke form
    }
    
    // 5. Redirect sukses
    return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diupload.');
}

    /**
     * Menampilkan pratinjau file (untuk tombol "Lihat").
     * Hanya berfungsi untuk PDF, selain itu akan langsung diunduh.
     */
    public function preview($id)
    {
        $document = Document::findOrFail($id);

        // Pastikan file ada di disk 'public'
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($document->file_path);
        
        // Cek ekstensi file
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        // Jika bukan PDF, langsung panggil fungsi download
        if (strtolower($extension) !== 'pdf') {
            return $this->download($document);
        }

        // Jika PDF, tampilkan inline di browser
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }

    /**
     * Mengunduh file (untuk tombol "Unduh").
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Pastikan file ada di disk 'public'
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        // Ambil nama asli file atau buat nama aman dari judul
        $originalName = pathinfo($document->file_path, PATHINFO_FILENAME);
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $downloadName = $originalName . '.' . $extension;

        return response()->download(Storage::disk('public')->path($document->file_path), $downloadName);
    }

    /**
     * Menghapus dokumen (untuk tombol "Hapus").
     */
    public function destroy($id)
    {
        $this->authorize('manage-documents');

        $document = Document::findOrFail($id);

        // 1. Hapus file dari storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // 2. Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}