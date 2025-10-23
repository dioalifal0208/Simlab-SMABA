<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User; // <-- Import User
use App\Models\StockRequest;
use App\Notifications\StockRequested; // <-- Import Notifikasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification; // <-- Import Notifikasi

class StockRequestController extends Controller
{
    /**
     * Menyimpan permintaan restock baru.
     */
    public function store(Request $request, Item $item)
    {
        // Cek untuk mencegah spam: Apakah user ini sudah pernah request item ini?
        $existingRequest = StockRequest::where('item_id', $item->id)
                                    ->where('user_id', Auth::id())
                                    ->where('status', 'requested')
                                    ->exists();

        if ($existingRequest) {
            return back()->with('success', 'Anda sudah pernah mengirim permintaan restock untuk item ini.');
        }

        // Buat permintaan baru
        $stockRequest = StockRequest::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'status'  => 'requested'
        ]);

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new StockRequested($stockRequest));

        return back()->with('success', 'Permintaan restock telah dikirim ke Admin.');
    }
}