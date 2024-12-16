<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'invoice_number', 'total_amount', 'status'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}

