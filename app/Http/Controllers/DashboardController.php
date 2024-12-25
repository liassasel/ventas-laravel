<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Sale;
use App\Models\TechnicalService;
use App\Models\Supplier;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalUsers' => User::count(),
            'totalSuppliers' => Supplier::count(),
            'totalSales' => Sale::count(),
            'totalServices' => TechnicalService::count(),
            'settings' => SystemSetting::first()

        ];

        return view('admin.dashboard', $data);
    }
}

