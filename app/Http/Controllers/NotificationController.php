<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
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
}
