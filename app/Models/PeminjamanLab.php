<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab;

class PeminjamanLab extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Mendefinisikan relasi ke model User (peminjam).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Lab yang dipinjam.
     */
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}