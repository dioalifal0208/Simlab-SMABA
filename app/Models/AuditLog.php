<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /**
     * Table name
     */
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable action name.
     */
    public function getActionLabel(): string
    {
        return match($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'failed_login' => 'Login Gagal',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get model display name.
     */
    public function getModelName(): string
    {
        if (!$this->model) {
            return '-';
        }

        $modelName = class_basename($this->model);
        
        return match($modelName) {
            'Item' => 'Item',
            'Loan' => 'Peminjaman',
            'Booking' => 'Booking',
            'User' => 'User',
            'Document' => 'Dokumen',
            'DamageReport' => 'Laporan Kerusakan',
            'PracticumModule' => 'Modul Praktikum',
            'Auth' => 'Autentikasi',
            default => $modelName,
        };
    }
}
