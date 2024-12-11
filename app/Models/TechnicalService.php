<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_phone',
        'client_dni',
        'client_ruc',
        'invoice_date',
        'guide_number',
        'brand',
        'model',
        'serial_number',
        'processor',
        'ram',
        'hard_drive',
        'diagnosis',
        'problem',
        'solution',
        'service_price',
        'status',
        'repair_status',
        'delivery_status',
        'seller_id',
        'order_date',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'order_date' => 'datetime',
        'service_price' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}

