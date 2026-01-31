<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBookingRequest extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $this->booking->loadMissing('user');

        $userName = $this->booking->user->name ?? 'Seorang pengguna';

        return [
            'user_id'   => $this->booking->user_id,
            'user_name' => $userName,
            'message'   => $userName . ' mengajukan booking lab baru.',
            'url'       => route('bookings.show', $this->booking->id),
        ];
    }
}

