<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Booking;
use App\Models\MaintenanceLog;
use App\Models\Document;
use App\Models\DamageReport;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;




class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'laboratorium',
        'nomor_induk',
        'phone_number',
        'kelas',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'current_session_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_expires_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
        ];
    }
    // TAMBAHKAN FUNGSI INI DI SINI
    /**
     * Get all of the items for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

}
