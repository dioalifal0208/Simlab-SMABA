<?php

namespace App\Notifications;

use App\Models\Loan; // <-- Import model Loan
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanStatusUpdated extends Notification
{
    use Queueable;

    protected $loan;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Tentukan channel pengiriman (hanya database).
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Akan dikirim ke ikon lonceng
    }

    /**
     * Ubah notifikasi menjadi format array (untuk disimpan ke database).
     */
    public function toDatabase(object $notifiable): array
    {
        // Tentukan pesan berdasarkan status
        $statusText = '';
        if ($this->loan->status == 'approved') {
            $statusText = 'telah DISETUJUI';
        } elseif ($this->loan->status == 'rejected') {
            $statusText = 'telah DITOLAK';
        }

        // Ambil nama item pertama untuk pesan yang lebih jelas
        $itemName = $this->loan->items()->first()->nama_alat ?? 'peminjaman Anda';
        if ($this->loan->items()->count() > 1) {
            $itemName .= ' (dan lainnya)';
        }

        return [
            'message' => "Pengajuan peminjaman ($itemName) Anda $statusText.",
            'url' => route('loans.show', $this->loan->id), // Link ke halaman detail
        ];
    }
}