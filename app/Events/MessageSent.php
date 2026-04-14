<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->contact_conversation_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id'              => $this->message->id,
            'body'            => $this->message->body,
            'sender_type'     => $this->message->sender_type,
            'sender_id'       => $this->message->sender_id,
            'created_at'      => $this->message->created_at->toIso8601String(),
            'conversation_id' => $this->message->contact_conversation_id,
        ];
    }
}
