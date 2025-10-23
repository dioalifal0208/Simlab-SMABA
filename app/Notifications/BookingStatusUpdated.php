<?php

namespace App\Notifications;

use App\Models\Booking; // <-- Import model Booking
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Akan dikirim ke ikon lonceng
    }

    public function toDatabase(object $notifiable): array
    {
        // Tentukan pesan berdasarkan status
        $statusText = '';
        if ($this->booking->status == 'approved') {
            $statusText = 'telah DISETUJUI';
        } elseif ($this->booking->status == 'rejected') {
            $statusText = 'telah DITOLAK';
        }

        return [
            'message' => 'Pengajuan booking lab Anda (' . $this->booking->tujuan_kegiatan . ') ' . $statusText,
            'url' => route('bookings.show', $this->booking->id),
        ];
    }
}