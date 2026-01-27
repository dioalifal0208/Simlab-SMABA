<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'last_message_at',
    ];

    protected $dates = [
        'last_message_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(ContactMessage::class);
    }
}
