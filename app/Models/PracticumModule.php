<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PracticumModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    /**
     * Relasi ke User (pembuat modul).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-many ke Item (alat/bahan yang dibutuhkan).
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_practicum_module')
                    ->withTimestamps(); // Jika Anda menggunakan timestamps di pivot table
                    // ->withPivot('quantity_needed'); // Jika Anda menambahkan kolom lain di pivot
    }
}