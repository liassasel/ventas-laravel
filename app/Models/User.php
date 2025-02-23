<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_technician',
        'is_seller',
        'can_add_products',
        'username',
        'dni',
        'profile_picture',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_technician' => 'boolean',
        'is_active' => 'boolean',
        'is_seller' => 'boolean',
        'can_add_products' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function isTechnician()
    {
        return $this->is_technician;
    }

    /**
     * Get the technical services created by this user.
     */
    public function technicalServices()
    {
        return $this->hasMany(TechnicalService::class, 'user_id');
    }

    /**
     * Get the technical services where this user is the seller.
     */
    public function salesServices()
    {
        return $this->hasMany(TechnicalService::class, 'seller_id');
    }

    public function sales()
{
    return $this->hasMany(Sale::class);
}

public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function canAccessSystem()
    {
        if ($this->is_admin) {
            return true;
        }

        $now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek;

        if ($dayOfWeek === Carbon::SUNDAY) {
            return false;
        }

        $settings = SystemSetting::first();

        if (!$settings->is_system_active) {
            return false;
        }

        $startTime = Carbon::createFromTimeString($settings->system_start_time);
        $endTime = Carbon::createFromTimeString($settings->system_end_time);

        return $now->between($startTime, $endTime);
    }
}

