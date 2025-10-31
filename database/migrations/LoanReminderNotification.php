<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class LoanReminderNotification extends Notification
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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Kita akan kirim ke database (ikon lonceng) dan email
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $returnDate = Carbon::parse($this->loan->tanggal_estimasi_kembali)->translatedFormat('l, d F Y');

        return (new MailMessage)
                    ->subject('Pengingat Pengembalian Peminjaman Alat')
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Ini adalah pengingat bahwa peminjaman alat Anda akan jatuh tempo pada **' . $returnDate . '**.')
                    ->line('Mohon untuk mempersiapkan dan mengembalikan item yang dipinjam tepat waktu.')
                    ->action('Lihat Detail Peminjaman', route('loans.show', $this->loan->id))
                    ->line('Terima kasih atas perhatian Anda.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Peminjaman Anda akan jatuh tempo besok. Mohon segera dikembalikan.',
            'url' => route('loans.show', $this->loan->id),
        ];
    }
}