<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    $conversation = \App\Models\ContactConversation::find($id);
    if (!$conversation) return false;
    
    // Admin can access all chats, users can only access their own
    return $user->role === 'admin' || (int) $user->id === (int) $conversation->user_id;
});

Broadcast::channel('typing.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
