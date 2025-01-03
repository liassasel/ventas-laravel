<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentItem extends Model
{
    protected $fillable = [
        'shipment_id',
        'name',
        'model',
        'brand',
        'quantity',
        'unit_price',
        'unit_price_dollars',
        'total_price',
        'total_price_dollars'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'unit_price_dollars' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_price_dollars' => 'decimal:2'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

