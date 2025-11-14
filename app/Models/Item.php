<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ItemImage;


class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_alat',
        'tipe',
        'jumlah',
        'stok_minimum',
        'satuan',
        'kondisi',
        'lokasi_penyimpanan',
        'kode_inventaris',
        'tahun_pengadaan',
        'deskripsi', // Mengganti 'keterangan' menjadi 'deskripsi' agar konsisten
        'user_id'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function loans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_item')->withPivot('jumlah')->withTimestamps();
    }

    /**
     * PENAMBAHAN: Relasi ke peminjaman yang sedang aktif (status 'approved').
     * Ini akan memperbaiki error 'undefined relationship'.
     */
    public function activeLoans(): BelongsToMany
    {
        return $this->belongsToMany(Loan::class, 'loan_item')
                    ->where('status', 'approved');
    }
    // (Di dalam kelas Item)
    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class)->latest(); // Otomatis urutkan dari terbaru
    }
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }
    public function practicumModules(): BelongsToMany
    {
        return $this->belongsToMany(PracticumModule::class, 'item_practicum_module')
                    ->withTimestamps(); // Jika Anda menggunakan timestamps di pivot table
                    // ->withPivot('quantity_needed'); // Jika Anda menambahkan kolom lain di pivot
    }
    public function stockRequests()
{
    return $this->hasMany(StockRequest::class);
}

    // ==============================================
    // ## RELASI BARU UNTUK GALERI GAMBAR ##
    // ==============================================
    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class);
    }

    public function mainImage(): HasOne
    {
        return $this->hasOne(ItemImage::class)->oldestOfMany();
    }
}