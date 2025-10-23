<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Item;
use Illuminate\Database\Eloquent\Relations\HasMany;




class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
