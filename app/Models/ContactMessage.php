<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_conversation_id',
        'sender_type',
        'sender_id',
        'body',
    ];

    public function conversation()
    {
        return $this->belongsTo(ContactConversation::class, 'contact_conversation_id');
    }
}
