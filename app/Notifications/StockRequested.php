<?php

namespace App\Notifications;

use App\Models\StockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockRequested extends Notification
{
    use Queueable;
    protected $stockRequest;

    public function __construct(StockRequest $stockRequest)
    {
        // PERBAIKAN: Langsung muat relasi di sini untuk mencegah N+1 Query
        $this->stockRequest = $stockRequest->load(['item', 'user']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        // Sekarang $this->stockRequest->item dan ->user sudah pasti ada
        $itemName = $this->stockRequest->item->nama_alat;
        $userName = $this->stockRequest->user->name;

        return [
            'message' => "$userName meminta restock untuk $itemName.",
            'url' => route('items.show', $this->stockRequest->item_id),
        ];
    }
}