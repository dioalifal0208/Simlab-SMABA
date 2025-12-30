<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;


class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_user_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Batasi query agar hanya mengembalikan dokumen yang dapat diakses oleh user tertentu.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->role === 'admin') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere(function (Builder $adminDocs) use ($user) {
                    $adminDocs->whereHas('user', fn (Builder $u) => $u->where('role', 'admin'))
                        ->where(function (Builder $target) use ($user) {
                            $target->whereNull('target_user_id')
                                   ->orWhere('target_user_id', $user->id);
                        });
                });
        });
    }

    /**
     * Cek apakah dokumen boleh diakses oleh user tertentu.
     */
    public function isVisibleTo(User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($this->user_id === $user->id) {
            return true;
        }

        $uploaderRole = $this->user?->role;

        if ($uploaderRole === 'admin') {
            return $this->target_user_id === null
                || (int) $this->target_user_id === (int) $user->id;
        }

        return false;
    }
}
