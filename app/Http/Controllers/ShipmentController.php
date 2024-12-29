<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Shipment;
use App\Models\Product;
use App\Models\ShipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['supplier', 'items.product'])
            ->latest()
            ->paginate(10);
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('shipments.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:255',
            'arrival_date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calcular el monto total
            $totalAmount = 0;
            foreach ($request->products as $product) {
                $totalAmount += $product['quantity'] * $product['unit_price'];
            }

            // Crear el shipment
            $shipment = Shipment::create([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'arrival_date' => $request->arrival_date,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);

            // Crear los items del shipment
            foreach ($request->products as $product) {
                $shipment->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total_price' => $product['quantity'] * $product['unit_price'],
                ]);

                // Actualizar el stock del producto
                $productModel = Product::find($product['product_id']);
                $productModel->increment('stock', $product['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('shipments.index')
                ->with('success', 'Cargamento registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al registrar el cargamento: ' . $e->getMessage());
        }
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['supplier', 'items.product']);
        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $shipment->load(['supplier', 'items.product']);
        return view('shipments.edit', compact('shipment', 'suppliers', 'products'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'required|string|max:255',
            'arrival_date' => 'required|date',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Revertir el stock anterior
            foreach ($shipment->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Calcular el nuevo monto total
            $totalAmount = 0;
            foreach ($request->products as $product) {
                $totalAmount += $product['quantity'] * $product['unit_price'];
            }

            // Actualizar el shipment
            $shipment->update([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'arrival_date' => $request->arrival_date,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
            ]);

            // Eliminar los items anteriores
            $shipment->items()->delete();

            // Crear los nuevos items
            foreach ($request->products as $product) {
                $shipment->items()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                    'total_price' => $product['quantity'] * $product['unit_price'],
                ]);

                // Actualizar el stock del producto
                $productModel = Product::find($product['product_id']);
                $productModel->increment('stock', $product['quantity']);
            }

            DB::commit();

            return redirect()
                ->route('shipments.index')
                ->with('success', 'Cargamento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el cargamento: ' . $e->getMessage());
        }
    }

    public function destroy(Shipment $shipment)
    {
        try {
            DB::beginTransaction();

            // Revertir el stock de los productos
            foreach ($shipment->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Eliminar el shipment (esto también eliminará los items por la relación cascade)
            $shipment->delete();

            DB::commit();

            return redirect()
                ->route('shipments.index')
                ->with('success', 'Cargamento eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el cargamento: ' . $e->getMessage());
        }
    }
}

