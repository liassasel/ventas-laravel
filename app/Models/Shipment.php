<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'store_id',
        'arrival_date',
        'total_amount',
        'total_amount_usd',
        'status',
        'notes'
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_amount_usd' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}

