<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 
        'user_id', 
        'total_amount', 
        'status',
        'cliente_nombre',
        'cliente_telefono',
        'cliente_correo',
        'cliente_ruc',
        'cliente_dni',
        'numero_guia',
        'fecha_facturacion'
    ];

    protected $casts = [
        'fecha_facturacion' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}

