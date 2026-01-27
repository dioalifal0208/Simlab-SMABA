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
            ->get(['id', 'sender_type', 'body', 'created_at']);

        return response()->json([
            'conversation_id' => $conversation->id,
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
