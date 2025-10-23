<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'description',
        'photo',
        'status',
    ];

    /**
     * Mendapatkan item yang terkait dengan laporan kerusakan.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Mendapatkan pengguna yang membuat laporan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}