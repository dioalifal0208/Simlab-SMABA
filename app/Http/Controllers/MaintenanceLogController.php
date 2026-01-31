<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\MaintenanceLog; // <-- Import model baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MaintenanceLogController extends Controller
{
    use AuthorizesRequests;

    public function index(Item $item)
    {
        $this->authorize('is-admin');

        // Ambil semua log perawatan untuk item ini
        // Kita gunakan relasi yang sudah kita buat di Model Item
        $maintenanceLogs = $item->maintenanceLogs()->with('user')->paginate(10);

        // Kirim data item DAN data log ke view
        return view('maintenance.index', compact('item', 'maintenanceLogs'));
    }

    public function store(Request $request, Item $item)
    {
        $this->authorize('is-admin');

        $validated = $request->validate([
            'tanggal_perawatan' => 'required|date',
            'hasil' => 'required|string|max:255',
            'masalah_ditemukan' => 'required|string',
            'tindakan_perbaikan' => 'required|string',
            'biaya' => 'nullable|integer|min:0',
        ]);

        // Buat log baru menggunakan relasi
        $item->maintenanceLogs()->create([
            'user_id' => Auth::id(),
            'tanggal_perawatan' => $validated['tanggal_perawatan'],
            'hasil' => $validated['hasil'],
            'masalah_ditemukan' => $validated['masalah_ditemukan'],
            'tindakan_perbaikan' => $validated['tindakan_perbaikan'],
            'biaya' => $validated['biaya'],
        ]);

        // PERUBAHAN: Arahkan ke halaman detail item, bukan kembali ke form.
        // Kita menggunakan route 'items.show' dan memberikan ID item yang sedang dirawat.
        return redirect()->route('items.show', $item->id)->with('success', 'Catatan perawatan berhasil ditambahkan.');
    }
}