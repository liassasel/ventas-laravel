<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Sale;
use App\Models\TechnicalService;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalUsers' => User::count(),
            'totalSuppliers' => Supplier::count(),
            'totalSales' => Sale::count(),
            'totalServices' => TechnicalService::count(),
        ];

        $stores = Store::all();
        $users = User::all();
        $selectedStore = $request->input('store_id', 'all');
        $selectedUser = $request->input('user_id', 'all');
        $selectedPeriod = $request->input('period', 'day');
        $customStartDate = $request->input('start_date');
        $customEndDate = $request->input('end_date');

        $salesQuery = Sale::query();

        if ($selectedStore !== 'all') {
            $salesQuery->where('store_id', $selectedStore);
        }

        if ($selectedUser !== 'all') {
            $salesQuery->where('user_id', $selectedUser);
        }

        if ($customStartDate && $customEndDate) {
            $salesQuery->whereBetween('created_at', [$customStartDate, $customEndDate]);
        } else {
            switch ($selectedPeriod) {
                case 'day':
                    $salesQuery->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $salesQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $salesQuery->whereMonth('created_at', Carbon::now()->month);
                    break;
            }
        }

        $salesStatistics = $salesQuery->with(['user', 'store'])
            ->select('user_id', 'store_id', DB::raw('COUNT(*) as total_sales'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('user_id', 'store_id')
            ->get();

        $topProducts = Sale::with('items.product')
            ->whereHas('items')
            ->get()
            ->flatMap(function ($sale) {
                return $sale->items;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'name' => $product->name,
                    'total_quantity' => $items->sum('quantity'),
                    'total_amount' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('total_quantity')
            ->values();

        $topProductsByStore = Store::with(['sales.items.product'])
            ->get()
            ->mapWithKeys(function ($store) {
                $products = $store->sales->flatMap(function ($sale) {
                    return $sale->items;
                })
                ->groupBy('product_id')
                ->map(function ($items) {
                    $product = $items->first()->product;
                    return [
                        'name' => $product->name,
                        'total_quantity' => $items->sum('quantity'),
                        'total_amount' => $items->sum(function ($item) {
                            return $item->quantity * $item->price;
                        }),
                    ];
                })
                ->sortByDesc('total_quantity')
                ->values();

                return [$store->name => $products];
            });

        $userStatistics = User::with(['sales' => function ($query) use ($selectedStore, $selectedPeriod, $customStartDate, $customEndDate) {
            if ($selectedStore !== 'all') {
                $query->where('store_id', $selectedStore);
            }
            if ($customStartDate && $customEndDate) {
                $query->whereBetween('created_at', [$customStartDate, $customEndDate]);
            } else {
                switch ($selectedPeriod) {
                    case 'day':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', Carbon::now()->month);
                        break;
                }
            }
        }])->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'total_sales' => $user->sales->count(),
                'total_amount_soles' => $user->sales->sum('total_amount'),
                'total_amount_dollars' => $user->sales->sum('total_amount') / 3.7, // Asumiendo un tipo de cambio fijo de 3.7
            ];
        })->sortByDesc('total_sales');

        $salesByStore = Store::with(['sales' => function ($query) use ($selectedPeriod, $customStartDate, $customEndDate) {
            if ($customStartDate && $customEndDate) {
                $query->whereBetween('created_at', [$customStartDate, $customEndDate]);
            } else {
                switch ($selectedPeriod) {
                    case 'day':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', Carbon::now()->month);
                        break;
                }
            }
        }])->get()->map(function ($store) {
            return [
                'name' => $store->name,
                'total_sales' => $store->sales->count(),
                'total_amount' => $store->sales->sum('total_amount'),
            ];
        })->sortByDesc('total_sales');

        $data['salesStatistics'] = $salesStatistics;
        $data['topProducts'] = $topProducts;
        $data['topProductsByStore'] = $topProductsByStore;
        $data['stores'] = $stores;
        $data['users'] = $users;
        $data['selectedStore'] = $selectedStore;
        $data['selectedUser'] = $selectedUser;
        $data['selectedPeriod'] = $selectedPeriod;
        $data['userStatistics'] = $userStatistics;
        $data['salesByStore'] = $salesByStore;
        $data['customStartDate'] = $customStartDate;
        $data['customEndDate'] = $customEndDate;

        return view('admin.dashboard', $data);
    }
}

