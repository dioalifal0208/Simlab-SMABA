<?php

namespace App\Notifications;

use App\Models\Loan; // <-- Import model Loan
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NewLoanRequest extends Notification
{
    use Queueable;

    protected $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     * Kita hanya akan menggunakan 'database'
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // <-- Menyimpan notifikasi ke database
    }

    /**
     * Get the array representation of the notification.
     * Ini adalah data yang akan disimpan di database
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $userName = $this->loan->user->name; // Nama user yang mengajukan

        return [
            'user_id' => $this->loan->user_id,
            'user_name' => $userName,
            'message' => $userName . ' mengajukan peminjaman baru.',
            'url' => route('loans.show', $this->loan->id), // Link saat notifikasi diklik
        ];
    }
}