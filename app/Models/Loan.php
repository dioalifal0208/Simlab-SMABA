<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'tanggal_pinjam',
        'tanggal_kembali',
        'catatan',
        'tanggal_estimasi_kembali',
        'admin_notes', // <-- Saya juga tambahkan admin_notes yang sudah kita buat
    ];

    /**
     * PENAMBAHAN: The attributes that should be cast.
     * Ini akan otomatis mengubah string tanggal dari DB menjadi objek Carbon.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_estimasi_kembali' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    /**
     * Sebuah peminjaman (Loan) dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sebuah peminjaman (Loan) bisa memiliki banyak Item.
     */
    public function items(): BelongsToMany
    {
        // Menentukan relasi many-to-many ke model Item
        // dengan tabel pivot 'loan_item'
        // dan menyertakan data dari kolom 'jumlah' di tabel pivot.
        return $this->belongsToMany(Item::class, 'loan_item')->withPivot('jumlah')->withTimestamps();
    }
}