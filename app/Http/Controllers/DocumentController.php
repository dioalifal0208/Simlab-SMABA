<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- PENTING
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar dokumen dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Document::with(['user:id,name,role', 'targetUser:id,name'])
            ->visibleTo($user)
            ->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->paginate(12); // Menampilkan 12 dokumen per halaman

        $targetUsers = $user->role === 'admin'
            ? User::where('role', 'guru')->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('documents.index', compact('documents', 'targetUsers'));
    }

    /**
     * Menyimpan dokumen yang baru diunggah.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // 1. Validasi request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480', // 20MB
            'target_user_id' => $user->role === 'admin'
                ? ['required', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'guru'))]
                : ['prohibited'],
        ]);

        try {
            // 2. Simpan file dan dapatkan data
            $file = $request->file('file');
            $path = $file->store('documents', 'public');
            $originalName = $file->getClientOriginalName();
            $fileType = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            // 3. Buat record di database
            Document::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'file_path' => $path,
                'file_name' => $originalName,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'user_id' => $user->id,
                'target_user_id' => $user->role === 'admin'
                    ? $validated['target_user_id'] ?? null
                    : null,
            ]);
        } catch (\Exception $e) {
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
    public function preview(Document $document)
    {
        $document->loadMissing('user');
        $this->ensureDocumentIsAccessible($document);

        // Pastikan file ada di disk 'public'
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($document->file_path);
        
        // Cek ekstensi file
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        // Jika bukan PDF, langsung panggil fungsi download
        if (strtolower($extension) !== 'pdf') {
            return $this->download($document); // Download hanya dipanggil saat pratinjau gagal karena non-PDF
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
        $document->loadMissing('user');
        $this->ensureDocumentIsAccessible($document);

        // Pastikan file ada di disk 'public'
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        // Pakai nama asli jika ada, fallback ke nama file di storage
        $downloadName = $document->file_name ?? basename($document->file_path);

        return response()->download(Storage::disk('public')->path($document->file_path), $downloadName);
    }

    /**
     * Menghapus dokumen (untuk tombol "Hapus").
     */
    public function destroy($id)
    {
        $this->authorize('manage-documents');

        $document = Document::with('user')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && (int) $document->user_id !== (int) Auth::id()) {
            abort(403, 'Anda hanya dapat menghapus dokumen milik Anda sendiri.');
        }

        // 1. Hapus file dari storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // 2. Hapus record dari database
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Pastikan user yang sedang login berhak mengakses dokumen.
     */
    private function ensureDocumentIsAccessible(Document $document): void
    {
        if (! $document->isVisibleTo(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }
    }
}
