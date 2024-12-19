<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductSold;
use App\Models\Inventory;
use App\Models\Category;
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
    $products = Product::with('category')
        ->where('stock', '>', 0)
        ->get();
    $categories = Category::all();
    $lastSale = Sale::latest()->first();
    $numeroGuia = $lastSale ? str_pad($lastSale->id + 1, 5, '0', STR_PAD_LEFT) : '00001';
    
    return view('sales.create', compact('stores', 'products', 'categories', 'numeroGuia'));
}

public function getProductsByStore(Request $request)
{
    $storeId = $request->input('store_id');
    $search = $request->input('search');
    $categoryId = $request->input('category_id');

    $query = Product::with('category')
        ->where('stock', '>', 0);

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('code', 'LIKE', "%{$search}%");
        });
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($storeId) {
        $query->whereHas('inventories', function ($q) use ($storeId) {
            $q->where('store_id', $storeId)
              ->where('quantity', '>', 0);
        });
    }

    $products = $query->get();

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
            'cliente_telefono' => 'required|string|max:20',
            'cliente_correo' => 'required|email|max:255',
            'cliente_ruc' => 'required|string|max:20',
            'cliente_dni' => 'required|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $lastSale = Sale::latest()->first();
            $numeroGuia = $lastSale ? str_pad($lastSale->id + 1, 5, '0', STR_PAD_LEFT) : '00001';

            $sale = Sale::create([
                'store_id' => $validatedData['store_id'],
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'status' => 'completed',
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

                $inventory = Inventory::where('store_id', $validatedData['store_id'])
                                      ->where('product_id', $product->id)
                                      ->first();

                if (!$inventory || $inventory->quantity < $quantity) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $inventory->quantity -= $quantity;
                $inventory->save();

                $saleItem = $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price_soles,
                ]);

                $totalAmount += $saleItem->quantity * $saleItem->price;

                if ($inventory->quantity == 0) {
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

    public function edit(Sale $sale)
    {
        $stores = Store::all();
        return view('sales.edit', compact('sale', 'stores'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validatedData = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_correo' => 'nullable|email|max:255',
            'cliente_ruc' => 'nullable|string|max:20',
            'cliente_dni' => 'nullable|string|max:20',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        DB::beginTransaction();

        try {
            $sale->update($validatedData);

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', 'Venta actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        try {
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->stock += $item->quantity;
                $product->save();
            }

            $sale->items()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Venta eliminada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function generateInvoice(Sale $sale)
    {
        $sale->load('items.product', 'store', 'user');
        // Here you would generate the invoice, possibly using a PDF library
        // For this example, we'll just return a view
        return view('sales.invoice', compact('sale'));
    }
}