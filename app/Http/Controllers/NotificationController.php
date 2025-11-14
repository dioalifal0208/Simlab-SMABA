<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(string $id)
    {
        $notification = DatabaseNotification::find($id);
        if (!$notification) {
            abort(404);
        }

        if ((int) Auth::id() !== (int) $notification->notifiable_id) {
            abort(403);
        }

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('dashboard');
        return redirect($url);
    }

    /**
     * Ringkasan notifikasi untuk polling Ajax (ikon lonceng).
     */
    public function summary(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'unread_count'  => 0,
                'notifications' => [],
            ]);
        }

        $user = Auth::user();

        return response()->json([
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $user->unreadNotifications()
                ->latest()
                ->take(5)
                ->get()
                ->map(function (DatabaseNotification $notification) {
                    return [
                        'id'               => $notification->id,
                        'message'          => $notification->data['message'] ?? '',
                        'created_at_human' => $notification->created_at->diffForHumans(),
                        'url'              => route('notifications.read', $notification->id),
                    ];
                }),
        ]);
    }
}
