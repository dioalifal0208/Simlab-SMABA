<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'file_path',
        'file_name', 'file_type', 'file_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}