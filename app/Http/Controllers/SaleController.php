<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductSold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('store', 'user')->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $stores = Store::all();
        $lastSale = Sale::latest()->first();
        $numeroGuia = $lastSale ? str_pad($lastSale->id + 1, 5, '0', STR_PAD_LEFT) : '00001';
        return view('sales.create', compact('stores', 'numeroGuia'));
    }

    public function getProductsByStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $search = $request->input('search');

        $products = Product::whereHas('inventories', function ($query) use ($storeId) {
            $query->where('store_id', $storeId)->where('quantity', '>', 0);
        })->where('name', 'LIKE', "%{$search}%")->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_correo' => 'nullable|email|max:255',
            'cliente_ruc' => 'nullable|string|max:20',
            'cliente_dni' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $lastSale = Sale::latest()->first();
            $numeroGuia = $lastSale ? str_pad($lastSale->id + 1, 5, '0', STR_PAD_LEFT) : '00001';

            $sale = Sale::create([
                'store_id' => $validatedData['store_id'],
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'status' => 'pending',
                'cliente_nombre' => $validatedData['cliente_nombre'],
                'cliente_telefono' => $validatedData['cliente_telefono'],
                'cliente_correo' => $validatedData['cliente_correo'],
                'cliente_ruc' => $validatedData['cliente_ruc'],
                'cliente_dni' => $validatedData['cliente_dni'],
                'numero_guia' => $numeroGuia,
                'fecha_facturacion' => Carbon::now(),
            ]);

            $totalAmount = 0;

            foreach ($validatedData['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                $quantity = $productData['quantity'];

                if ($product->stock < $quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->stock -= $quantity;
                $product->save();

                $saleItem = $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price_soles,
                    'is_service' => isset($productData['is_service']) ? $productData['is_service'] : false, //Added to handle potential missing key
                ]);

                $totalAmount += $saleItem->quantity * $saleItem->price;

                // Si el stock llega a 0, mover a productos vendidos
                if ($product->stock == 0) {
                    ProductSold::create([
                        'product_id' => $product->id,
                        'sale_id' => $sale->id,
                        'store_id' => $validatedData['store_id'],
                        'user_id' => Auth::id(),
                        'price' => $product->price_soles,
                        'serial' => $product->serial,
                        'fecha_venta' => now(),
                    ]);
                }
            }

            $sale->total_amount = $totalAmount;
            $sale->status = 'completed';
            $sale->save();


            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', 'Venta completada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product', 'store', 'user');
        return view('sales.show', compact('sale'));
    }
}

