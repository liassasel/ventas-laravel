<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['store', 'supplier'])->orderBy('created_at', 'desc')->paginate(10);
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $lastShipment = Shipment::latest()->first();
        $invoiceNumber = $lastShipment ? 'INV-' . str_pad($lastShipment->id + 1, 5, '0', STR_PAD_LEFT) : 'INV-00001';
        $stores = Store::all();
        $suppliers = Supplier::all();
        
        return view('shipments.create', compact('invoiceNumber', 'stores', 'suppliers'));
    }

    public function store(Request $request)
    {
        Log::info('Iniciando proceso de guardado de cargamento');
        Log::info('Datos recibidos:', $request->all());

        $validated = $request->validate([
            'invoice_number' => 'required|string|unique:shipments,invoice_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'arrival_date' => 'required|date',
            'store_id' => 'required|exists:stores,id',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        Log::info('Validación pasada correctamente');

        try {
            DB::beginTransaction();
            Log::info('Iniciando transacción DB');

            $totalAmount = 0;

            $shipment = Shipment::create([
                'invoice_number' => $validated['invoice_number'],
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'arrival_date' => $validated['arrival_date'],
                'total_amount' => 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Cargamento creado:', $shipment->toArray());

            foreach ($validated['products'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $itemTotal
                ]);
                $totalAmount += $itemTotal;
            }

            Log::info('Items de cargamento creados. Total: ' . $totalAmount);

            $shipment->update(['total_amount' => $totalAmount]);

            DB::commit();
            Log::info('Transacción completada exitosamente');

            return redirect()->route('shipments.index')->with('success', 'Cargamento creado correctamente. Total: S/. ' . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear el cargamento: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear el cargamento: ' . $e->getMessage());
        }
    }
}

