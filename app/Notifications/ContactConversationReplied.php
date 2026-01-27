<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContactConversationReplied extends Notification
{
    use Queueable;

    public function __construct(
        public int $conversationId,
        public string $body
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Balasan baru dari admin untuk pesan Anda.',
            'url'     => route('contact.conversations.index'),
            'body'    => $this->body,
        ];
    }
}
