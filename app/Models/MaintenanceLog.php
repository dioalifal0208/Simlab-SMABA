<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'item_id', 'user_id', 'tanggal_perawatan', 'hasil', 
        'masalah_ditemukan', 'tindakan_perbaikan', 'biaya'
    ];

    // Tentukan tipe data untuk tanggal
    protected $casts = [
        'tanggal_perawatan' => 'date',
    ];

    // Relasi: Log ini milik siapa?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Log ini untuk item apa?
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}