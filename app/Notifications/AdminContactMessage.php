<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminContactMessage extends Notification
{
    use Queueable;

    public function __construct(
        public string $namaPengirim,
        public string $emailPengirim,
        public string $pesan,
        public ?int $conversationId = null
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'       => "Pesan baru dari {$this->namaPengirim} ({$this->emailPengirim})",
            'url'           => $this->conversationId
                ? route('admin.contact-conversations.index', ['open' => $this->conversationId])
                : route('admin.contact-conversations.index'),
            'body'          => $this->pesan,
            'sender_name'   => $this->namaPengirim,
            'sender_email'  => $this->emailPengirim,
        ];
    }
}
