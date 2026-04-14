<?php

namespace App\Http\Controllers;

use App\Models\ContactConversation;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\AdminContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactConversationController extends Controller
{
    public function index(Request $request)
    {
        $conversation = ContactConversation::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'open', 'last_message_at' => now()]
        );

        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('contact.conversation', compact('conversation', 'messages'));
    }

    /**
     * Ambil pesan dalam percakapan untuk user (JSON).
     */
    public function messages(Request $request)
    {
        $conversation = ContactConversation::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => 'open', 'last_message_at' => now()]
        );

        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get(['id', 'sender_type', 'body', 'created_at', 'read_at']);

        // Mark all admin messages as read
        $unreadQuery = $conversation->messages()
            ->where('sender_type', 'admin')
            ->whereNull('read_at');
        
        $unreadMessagesCount = $unreadQuery->count();
        if ($unreadMessagesCount > 0) {
            $lastUnread = $unreadQuery->latest()->first();
            $unreadQuery->update(['read_at' => now()]);
            
            // Broadcast that user has read admin's messages
            // Channel: user.{adminId} (the sending admin)
            // Note: If multiple admins, we broadcast to the sender of the last unread
            broadcast(new \App\Events\MessageRead(
                $conversation->id,
                $lastUnread->id,
                $lastUnread->sender_id,
                auth()->id()
            ));
        }

        $lastAdminMessage = $conversation->messages()->where('sender_type', 'admin')->latest()->first();
        
        return response()->json([
            'conversation_id' => $conversation->id,
            'receiver_id'     => $lastAdminMessage ? $lastAdminMessage->sender_id : 2,
            'messages'        => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pesan' => 'required|string|max:500',
        ]);

        $user = $request->user();
        $conversation = ContactConversation::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'open', 'last_message_at' => now()]
        );

        $message = $conversation->messages()->create([
            'sender_type' => 'user',
            'sender_id'   => $user->id,
            'body'        => $validated['pesan'],
        ]);

        $conversation->update(['last_message_at' => now(), 'status' => 'open']);
        
        // Broadcast pesan real-time
        broadcast(new \App\Events\MessageSent($message))->toOthers();

        // Notifikasi ke admin
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new AdminContactMessage(
                    $user->name,
                    $user->email,
                    $message->body,
                    $conversation->id
                ));
            }
        } catch (\Throwable $th) {
            // abaikan kegagalan notifikasi
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Pesan terkirim.',
                'conversation_id' => $conversation->id,
                'data' => [
                    'id'          => $message->id,
                    'sender_type' => $message->sender_type,
                    'body'        => $message->body,
                    'created_at'  => $message->created_at,
                ],
            ]);
        }

        return back()->with('contact_submitted', 'Pesan terkirim. Admin akan membalas di percakapan ini.');
    }
}
