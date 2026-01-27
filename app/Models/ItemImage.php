<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['item_id', 'path'];

    /**
     * Get the item that owns the image.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}