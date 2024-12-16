<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ventas totales
        $totalSales = Sale::sum('total_amount');

        // Ventas por tienda
        $salesByStore = Store::withSum('sales', 'total_amount')
            ->orderByDesc('sales_sum_total_amount')
            ->get();

        // Ventas por usuario
        $salesByUser = User::withSum('sales', 'total_amount')
            ->orderByDesc('sales_sum_total_amount')
            ->get();

        // Ventas por mes (últimos 12 meses)
        $salesByMonth = Sale::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('dashboard', compact('totalSales', 'salesByStore', 'salesByUser', 'salesByMonth'));
    }
}

