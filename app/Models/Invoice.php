<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'sale_id',
        'serie',
        'correlativo',
        'xml',
        'hash',
        'cdr',
        'status'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

