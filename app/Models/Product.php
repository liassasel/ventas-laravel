<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'stock',
        'category_id',
        'serial',
        'model',
        'brand',
        'color',
        'price_dollars',
        'price_soles',
        'currency'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function mainStore()
    {
        return $this->belongsTo(Store::class, 'main_store_id');
    }
}

