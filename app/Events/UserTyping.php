<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $senderId;
    public $receiverId;
    public $senderName;

    /**
     * Create a new event instance.
     */
    public function __construct($senderId, $receiverId, $senderName)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->senderName = $senderName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('typing.' . $this->receiverId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'typing';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        \Illuminate\Support\Facades\Log::info('Broadcast typing event dispatched', [
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'name' => $this->senderName
        ]);

        return [
            'senderId' => $this->senderId,
            'senderName' => $this->senderName,
        ];
    }
}
