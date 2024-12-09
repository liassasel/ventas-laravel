<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name','description','price_soles', 'price_dollars', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
