<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['store', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('shipments.index', compact('shipments'));
    }

    public function create()
{
    $lastShipment = Shipment::latest()->first();
    $invoiceNumber = $lastShipment ? str_pad($lastShipment->id + 1, 5, '0', STR_PAD_LEFT) : '00001';
    $stores = Store::all();
    
    return view('shipments.create', compact('invoiceNumber', 'stores'));
}

public function store(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'invoice_number' => 'required|unique:shipments,invoice_number',
        'arrival_date' => 'required|date',
        'products' => 'required|array|min:1',
        'products.*.name' => 'required|string',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    try {
        DB::beginTransaction();

        $shipment = Shipment::create([
            'store_id' => $request->store_id,
            'invoice_number' => $request->invoice_number,
            'arrival_date' => $request->arrival_date,
            'notes' => $request->notes,
            'status' => 'pending',
            'total_amount' => 0,
        ]);

        $totalAmount = 0;

        foreach ($request->products as $productData) {
            $totalPrice = $productData['quantity'] * $productData['price'];
            
            ShipmentItem::create([
                'shipment_id' => $shipment->id,
                'name' => $productData['name'],
                'quantity' => $productData['quantity'],
                'unit_price' => $productData['price'],
                'total_price' => $totalPrice,
            ]);

            $totalAmount += $totalPrice;
        }

        $shipment->update(['total_amount' => $totalAmount]);

        DB::commit();

        return redirect()->route('shipments.index')
            ->with('success', 'Cargamento creado exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al crear el cargamento: ' . $e->getMessage())
            ->withInput();
    }
}

    public function show(Shipment $shipment)
    {
        $shipment->load(['store', 'supplier', 'items.product']);
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        if ($shipment->status !== 'pending') {
            return redirect()->route('shipments.index')
                ->with('error', 'Solo se pueden editar envíos pendientes.');
        }

        $suppliers = Supplier::all();
        $stores = Store::all();
        $products = Product::where('stock', '>', 0)->get();
        
        return view('shipments.edit', compact('shipment', 'suppliers', 'stores', 'products'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        if ($shipment->status !== 'pending') {
            return redirect()->route('shipments.index')
                ->with('error', 'Solo se pueden actualizar envíos pendientes.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'store_id' => 'required|exists:stores,id',
            'arrival_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Restaurar el stock de los productos anteriores
            foreach ($shipment->items as $item) {
                $item->product->update([
                    'stock' => $item->product->stock + $item->quantity
                ]);
            }

            $shipment->update([
                'supplier_id' => $request->supplier_id,
                'store_id' => $request->store_id,
                'arrival_date' => $request->arrival_date,
                'notes' => $request->notes,
            ]);

            // Eliminar items anteriores
            $shipment->items()->delete();

            $totalAmount = 0;

            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['id']);
                
                // Verificar si hay suficiente stock
                if ($product->stock < $productData['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->name}");
                }

                $totalPrice = $product->price_soles * $productData['quantity'];
                
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'unit_price' => $product->price_soles,
                    'total_price' => $totalPrice,
                ]);

                // Actualizar el stock del producto
                $product->update([
                    'stock' => $product->stock - $productData['quantity']
                ]);

                $totalAmount += $totalPrice;
            }

            $shipment->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('shipments.index')
                ->with('success', 'Envío actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el envío: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Shipment $shipment)
    {
        if ($shipment->status !== 'pending') {
            return redirect()->route('shipments.index')
                ->with('error', 'Solo se pueden eliminar envíos pendientes.');
        }

        try {
            DB::beginTransaction();
            
            // Restaurar el stock de los productos
            foreach ($shipment->items as $item) {
                $item->product->update([
                    'stock' => $item->product->stock + $item->quantity
                ]);
            }
            
            $shipment->items()->delete();
            $shipment->delete();
            
            DB::commit();

            return redirect()->route('shipments.index')
                ->with('success', 'Envío eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el envío: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        $shipment->update(['status' => $request->status]);

        return redirect()->route('shipments.index')
            ->with('success', 'Estado del envío actualizado exitosamente.');
    }

    public function getProductsBySupplier(Request $request)
    {
        $products = Product::where('supplier_id', $request->supplier_id)
            ->where('stock', '>', 0)
            ->get();
            
        return response()->json($products);
    }
}

