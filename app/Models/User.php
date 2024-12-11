<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_technician',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_technician' => 'boolean',
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
}

