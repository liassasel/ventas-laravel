<?php

namespace App\Http\Controllers;

use App\Models\ProductSold;
use Illuminate\Http\Request;

class ProductSoldController extends Controller
{
    public function index()
    {
        $productsSold = ProductSold::with(['product', 'store', 'user'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(15);
            
        return view('products_sold.index', compact('productsSold'));
    }
}

