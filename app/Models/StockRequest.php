<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- PENTING: Tambahkan ini

class StockRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['item_id', 'user_id', 'status'];

    /**
     * PENAMBAHAN: Relasi ke Item
     * Mendapatkan item yang diminta.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * PENAMBAHAN: Relasi ke User
     * Mendapatkan user yang meminta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}