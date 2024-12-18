<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSold extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sale_id',
        'store_id',
        'user_id',
        'price',
        'serial',
        'fecha_venta'
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
