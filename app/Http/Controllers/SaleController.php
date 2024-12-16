<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $products = Product::all();
        return view('sales.create', compact('stores', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.is_service' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'store_id' => $validatedData['store_id'],
                'user_id' => auth()->id(),
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $totalAmount = 0;

            foreach ($validatedData['products'] as $productData) {
                $product = Product::findOrFail($productData['id']);
                $quantity = $productData['quantity'];
                $isService = $productData['is_service'];

                if (!$isService) {
                    $inventory = Inventory::where('store_id', $validatedData['store_id'])
                        ->where('product_id', $product->id)
                        ->first();

                    if (!$inventory || $inventory->quantity < $quantity) {
                        throw new \Exception("Inventario insuficiente para el producto {$product->name}");
                    }

                    $inventory->quantity -= $quantity;
                    $inventory->save();
                }

                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'is_service' => $isService,
                ]);

                $totalAmount += $saleItem->quantity * $saleItem->price;
            }

            $sale->total_amount = $totalAmount;
            $sale->status = 'completed';
            $sale->save();

            $invoice = Invoice::create([
                'sale_id' => $sale->id,
                'invoice_number' => 'INV-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                'total_amount' => $totalAmount,
                'status' => 'paid',
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', 'Venta realizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product', 'store', 'user', 'invoice');
        return view('sales.show', compact('sale'));
    }
}

