<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_alat',
        'tipe',
        'jumlah',
        'stok_minimum',
        'satuan',
        'kondisi',
        'lokasi_penyimpanan',
        'deskripsi',
        'photo',
        'user_id',
        'kode_inventaris',
        'tahun_pengadaan',
    ];

    /**
     * Relasi ke user yang menambahkan item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke semua peminjaman yang terkait dengan item ini.
     */
    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_item')->withPivot('jumlah')->withTimestamps();
    }

    /**
     * PENAMBAHAN: Relasi ke peminjaman yang sedang aktif (status 'approved').
     * Inilah yang akan memperbaiki error.
     */
    public function activeLoans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_item')
                    ->where('status', 'approved');
    }

    public function practicumModules(): BelongsToMany
    {
        return $this->belongsToMany(PracticumModule::class, 'item_practicum_module');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }
}