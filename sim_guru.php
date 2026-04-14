<?php
// Wait for browser to log in
sleep(15);

$admin = \App\Models\User::where('email', 'v1-admin@test.com')->first();
$guru = \App\Models\User::where('email', 'v1-guru@test.com')->first();

if (!$admin || !$guru) {
    echo "Users not found\n";
    exit(1);
}

// Ensure Guru is the conversation owner for user-side messages 
// Actually, conversations are user_id based.
$conv = \App\Models\ContactConversation::firstOrCreate(['user_id' => $guru->id]);

$msg = $conv->messages()->create([
    'sender_type' => 'user',
    'sender_id' => $guru->id,
    'body' => 'Real-time test message from Guru! (' . date('H:i:s') . ')'
]);

broadcast(new \App\Events\MessageSent($msg))->toOthers();

echo "Message sent to conversation ID: " . $conv->id;
