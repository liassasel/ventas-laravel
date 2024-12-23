<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ruc_dni',
        'address',
        'phone',
        'email',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}

