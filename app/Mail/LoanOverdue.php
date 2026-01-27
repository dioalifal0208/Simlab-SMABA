<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanOverdue extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Loan $loan
    ) {
        $this->loan->load(['user', 'items']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $daysOverdue = now()->diffInDays($this->loan->tanggal_estimasi_kembali);
        
        return new Envelope(
            subject: "Peminjaman Terlambat ({$daysOverdue} Hari) - Lab SMABA",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-overdue',
            with: [
                'daysOverdue' => now()->diffInDays($this->loan->tanggal_estimasi_kembali),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
