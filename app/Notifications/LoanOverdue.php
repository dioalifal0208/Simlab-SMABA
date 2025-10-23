<?php
namespace App\Notifications;

use App\Models\Loan; // <-- Import model Loan
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanOverdue extends Notification
{
    use Queueable;

    protected $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Hanya simpan ke database (ikon lonceng)
    }

    public function toDatabase(object $notifiable): array
    {
        // Ambil nama item pertama sebagai contoh
        $firstItemName = $this->loan->items()->first()->nama_alat ?? 'peminjaman Anda';

        return [
            'message' => 'Peminjaman ' . $firstItemName . ' sudah melewati batas waktu pengembalian.',
            'url' => route('loans.show', $this->loan->id),
        ];
    }
}