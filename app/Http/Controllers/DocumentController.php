<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
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
        $this->authorize('manage-documents');

        $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240', // max 10MB
        ]);

        // Simpan file ke disk 'public' di dalam folder 'documents'
        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'title'     => $request->title,
            'file_path' => $path,
            'user_id'   => $request->user()->id,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Menampilkan pratinjau file (untuk tombol "Lihat").
     * Hanya berfungsi untuk PDF, selain itu akan langsung diunduh.
     */
    public function preview(Document $document)
    {
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
    public function download(Document $document)
    {
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
    public function destroy(Document $document)
    {
        $this->authorize('manage-documents');

        // 1. Hapus file dari storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // 2. Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}