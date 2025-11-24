<?php

namespace App\Http\Controllers;

use App\Models\ContactConversation;
use App\Models\ContactMessage;
use App\Notifications\ContactConversationReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminContactConversationController extends Controller
{
    public function index()
    {
        Gate::authorize('is-admin');

        $conversations = ContactConversation::with('user')
            ->orderByDesc('last_message_at')
            ->paginate(10);

        return view('admin.contact-conversations.index', compact('conversations'));
    }

    /**
     * JSON daftar percakapan untuk admin (widget).
     */
    public function listJson()
    {
        Gate::authorize('is-admin');

        $conversations = ContactConversation::with('user')
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function (ContactConversation $conv) {
                $latest = $conv->messages->first();
                return [
                    'id'              => $conv->id,
                    'user_name'       => $conv->user->name ?? 'Pengguna',
                    'user_email'      => $conv->user->email ?? '-',
                    'status'          => $conv->status,
                    'last_message'    => $latest?->body,
                    'last_message_at' => $latest?->created_at?->toDateTimeString(),
                ];
            });

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * JSON pesan per percakapan untuk admin (widget).
     */
    public function messagesJson(ContactConversation $conversation)
    {
        Gate::authorize('is-admin');

        $conversation->load('user');

        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get(['id', 'sender_type', 'body', 'created_at']);

        return response()->json([
            'conversation' => [
                'id'         => $conversation->id,
                'user_name'  => $conversation->user->name ?? 'Pengguna',
                'user_email' => $conversation->user->email ?? '-',
            ],
            'messages' => $messages,
        ]);
    }

    public function show(ContactConversation $conversation)
    {
        Gate::authorize('is-admin');

        $conversation->load(['user', 'messages' => function ($query) {
            $query->orderBy('created_at');
        }]);

        return view('admin.contact-conversations.show', compact('conversation'));
    }

    public function reply(Request $request, ContactConversation $conversation)
    {
        Gate::authorize('is-admin');

        $validated = $request->validate([
            'pesan' => 'required|string|max:500',
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'admin',
            'sender_id'   => $request->user()->id,
            'body'        => $validated['pesan'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status'          => 'open',
        ]);

        // Notifikasi ke pengguna
        try {
            if ($conversation->user) {
                $conversation->user->notify(new ContactConversationReplied($conversation->id, $message->body));
            }
        } catch (\Throwable $th) {
            // diamkan jika notifikasi gagal
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Balasan terkirim.',
                'data' => [
                    'id'          => $message->id,
                    'sender_type' => $message->sender_type,
                    'body'        => $message->body,
                    'created_at'  => $message->created_at,
                ],
            ]);
        }

        return back()->with('success', 'Balasan terkirim.');
    }
}
