<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guru_pengampu',
        'tujuan_kegiatan',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_peserta',
    ];

    /**
     * Tentukan tipe data untuk kolom tertentu.
     */
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Sebuah booking (Booking) dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}