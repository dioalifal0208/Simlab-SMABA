<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class Booking extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'laboratorium',
        'waktu_mulai',
        'waktu_selesai',
        'tujuan_kegiatan',
        'jumlah_peserta',
        'status',
        'admin_notes',
        'guru_pengampu',
        'mata_pelajaran',
        
        // Return Details
        'waktu_pengembalian',
        'kondisi_lab',
    ];

    /**
     * Tentukan tipe data untuk kolom tertentu.
     */
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'waktu_pengembalian' => 'datetime',
        'kondisi_lab' => 'array',
    ];

    /**
     * Sebuah booking (Booking) dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
