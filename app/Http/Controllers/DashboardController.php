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
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $selectedPeriod = $request->input('period', 'month');
            $customStartDate = $request->input('start_date');
            $customEndDate = $request->input('end_date');

            $salesQuery = Sale::query();

            if ($customStartDate && $customEndDate) {
                $salesQuery->whereBetween('created_at', [$customStartDate, $customEndDate]);
            } else {
                switch ($selectedPeriod) {
                    case 'week':
                        $salesQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $salesQuery->whereMonth('created_at', Carbon::now()->month);
                        break;
                    case 'year':
                        $salesQuery->whereYear('created_at', Carbon::now()->year);
                        break;
                }
            }

            $totalSales = $salesQuery->count();
            $totalAmountSoles = $salesQuery->sum('total_amount');
            $totalAmountDollars = $totalAmountSoles / 3.7; // Asumiendo un tipo de cambio fijo de 3.7

            $data = [
                'totalProducts' => Product::count(),
                'totalUsers' => User::count(),
                'totalSuppliers' => Supplier::count(),
                'totalSales' => $totalSales,
                'totalAmountSoles' => $totalAmountSoles,
                'totalAmountDollars' => $totalAmountDollars,
                'totalServices' => TechnicalService::count(),
            ];

            $stores = Store::all();
            $users = User::all();
            
            $topProducts = $salesQuery->with('items.product')
                ->get()
                ->flatMap(function ($sale) {
                    return $sale->items;
                })
                ->groupBy('product_id')
                ->map(function ($items) {
                    $product = $items->first()->product;
                    $totalAmountSoles = $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                    return [
                        'name' => $product->name,
                        'total_quantity' => $items->sum('quantity'),
                        'total_amount_soles' => $totalAmountSoles,
                        'total_amount_dollars' => $totalAmountSoles / 3.7,
                    ];
                })
                ->sortByDesc('total_quantity')
                ->take(10)
                ->values();

            $topProductsByStore = Store::with(['sales' => function ($query) use ($salesQuery) {
                    $query->whereIn('id', $salesQuery->pluck('id'));
                }, 'sales.items.product'])
                ->get()
                ->mapWithKeys(function ($store) {
                    $products = $store->sales->flatMap(function ($sale) {
                        return $sale->items;
                    })
                    ->groupBy('product_id')
                    ->map(function ($items) {
                        $product = $items->first()->product;
                        $totalAmountSoles = $items->sum(function ($item) {
                            return $item->quantity * $item->price;
                        });
                        return [
                            'name' => $product->name,
                            'total_quantity' => $items->sum('quantity'),
                            'total_amount_soles' => $totalAmountSoles,
                            'total_amount_dollars' => $totalAmountSoles / 3.7,
                        ];
                    })
                    ->sortByDesc('total_quantity')
                    ->take(5)
                    ->values();

                    return [$store->name => $products];
                });

            $userStatistics = User::with(['sales' => function ($query) use ($salesQuery) {
                $query->whereIn('id', $salesQuery->pluck('id'));
            }])->get()->map(function ($user) {
                $totalAmountSoles = $user->sales->sum('total_amount');
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_sales' => $user->sales->count(),
                    'total_amount_soles' => $totalAmountSoles,
                    'total_amount_dollars' => $totalAmountSoles / 3.7,
                ];
            })->sortByDesc('total_sales');

            $data['topProducts'] = $topProducts;
            $data['topProductsByStore'] = $topProductsByStore;
            $data['stores'] = $stores;
            $data['users'] = $users;
            $data['userStatistics'] = $userStatistics;
            $data['selectedPeriod'] = $selectedPeriod;
            $data['customStartDate'] = $customStartDate;
            $data['customEndDate'] = $customEndDate;

            return view('admin.dashboard', $data);
        } catch (\Exception $e) {
            Log::error('Error in DashboardController', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()
                ->route('dashboard')
                ->with('error', 'Ha ocurrido un error al cargar los datos del dashboard. Por favor, inténtelo de nuevo más tarde.');
        }
    }

    public function getUserStats(Request $request, User $user)
    {
        try {
            $period = $request->input('period', 'month');
            $customStartDate = $request->input('start_date');
            $customEndDate = $request->input('end_date');

            $query = $user->sales();

            if ($customStartDate && $customEndDate) {
                $query->whereBetween('created_at', [$customStartDate, $customEndDate]);
            } else {
                switch ($period) {
                    case 'week':
                        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', Carbon::now()->month);
                        break;
                    case 'year':
                        $query->whereYear('created_at', Carbon::now()->year);
                        break;
                }
            }

            $sales = $query->with('items.product')->get();

            $totalAmountSoles = $sales->sum('total_amount');
            $totalAmountDollars = $totalAmountSoles / 3.7;

            $stats = [
                'total_sales' => $sales->count(),
                'total_amount_soles' => $totalAmountSoles,
                'total_amount_dollars' => $totalAmountDollars,
                'products_sold' => $sales->flatMap(function ($sale) {
                    return $sale->items;
                })->groupBy(function ($item) {
                    return $item->product->name;
                })->map(function ($items) {
                    $totalSoles = $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                    return [
                        'quantity' => $items->sum('quantity'),
                        'total_soles' => $totalSoles,
                        'total_dollars' => $totalSoles / 3.7,
                    ];
                })->sortByDesc('quantity'),
                'daily_sales' => $sales->groupBy(function ($sale) {
                    return $sale->created_at->format('Y-m-d');
                })->map(function ($sales) {
                    $totalSoles = $sales->sum('total_amount');
                    return [
                        'count' => $sales->count(),
                        'total_soles' => $totalSoles,
                        'total_dollars' => $totalSoles / 3.7,
                    ];
                }),
            ];

            return view('admin.user_stats', compact('user', 'stats', 'period', 'customStartDate', 'customEndDate'));
        } catch (\Exception $e) {
            Log::error('Error in getUserStats', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()
                ->route('dashboard')
                ->with('error', 'Ha ocurrido un error al cargar las estadísticas del usuario. Por favor, inténtelo de nuevo más tarde.');
        }
    }
}

