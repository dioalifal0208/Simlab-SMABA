<?php
namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class AnnouncementController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengumuman.
     */
    public function index()
    {
        // Ambil semua pengumuman, yang aktif akan di atas
        $announcements = Announcement::with('user')
                            ->orderBy('status', 'desc') // 'active' akan di atas 'inactive'
                            ->latest()
                            ->paginate(10);
                            
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Menyimpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Transaksi Database: Matikan semua pengumuman lama, lalu buat yang baru.
        DB::transaction(function () use ($request) {
            // 1. Set semua pengumuman lain menjadi 'inactive'
            Announcement::where('status', 'active')->update(['status' => 'inactive']);

            // 2. Buat pengumuman baru sebagai 'active'
            Announcement::create([
                'user_id' => Auth::id(),
                'message' => $request->message,
                'status'  => 'active',
            ]);
        });

        return back()->with('success', 'Pengumuman baru telah dipublikasikan.');
    }

    /**
     * Menghapus (menonaktifkan) pengumuman.
     */
    public function destroy(Announcement $announcement)
    {
        // Kita tidak benar-benar menghapus, hanya menonaktifkan
        $announcement->update(['status' => 'inactive']);

        return back()->with('success', 'Pengumuman telah diarsipkan.');
    }
}