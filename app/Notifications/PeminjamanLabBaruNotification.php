<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PeminjamanLab;

class PeminjamanLabBaruNotification extends Notification
{
    use Queueable;

    protected $peminjaman;

    /**
     * Create a new notification instance.
     */
    public function __construct(PeminjamanLab $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Mengubah channel pengiriman ke 'database' untuk notifikasi dalam aplikasi
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data ini akan disimpan di kolom 'data' pada tabel 'notifications'
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'user_name' => $this->peminjaman->user->name, // Asumsi ada relasi 'user' di model PeminjamanLab
            'lab_name' => $this->peminjaman->lab->nama_lab, // Asumsi ada relasi 'lab' di model PeminjamanLab
            'message' => 'Pengajuan peminjaman lab baru dari ' . $this->peminjaman->user->name . '.',
            'url' => route('bookings.index'), // Fallback ke index booking
        ];
    }
}
