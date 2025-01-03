<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\Supplier;
use App\Models\Store;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        $shipments = Shipment::with(['store', 'supplier', 'items'])->orderBy('created_at', 'desc')->paginate(10);
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
            'products.*.model' => 'nullable|string',
            'products.*.brand' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.currency' => 'required|in:PEN,USD',
            'notes' => 'nullable|string',
        ]);

        Log::info('Validación pasada correctamente');

        try {
            DB::beginTransaction();
            Log::info('Iniciando transacción DB');

            $totalAmount = 0;
            $totalAmountUSD = 0;

            $shipment = Shipment::create([
                'invoice_number' => $validated['invoice_number'],
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'arrival_date' => $validated['arrival_date'],
                'total_amount' => 0,
                'total_amount_usd' => 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            Log::info('Cargamento creado:', $shipment->toArray());

            foreach ($validated['products'] as $item) {
                $priceInSoles = $item['currency'] === 'PEN' ? $item['price'] : $this->currencyService->convertDollarsToSoles($item['price']);
                $priceInDollars = $item['currency'] === 'USD' ? $item['price'] : $this->currencyService->convertSolesToDollars($item['price']);
                
                $itemTotal = $item['quantity'] * $priceInSoles;
                $itemTotalUSD = $item['quantity'] * $priceInDollars;
                
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'name' => $item['name'],
                    'model' => $item['model'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $priceInSoles,
                    'unit_price_dollars' => $priceInDollars,
                    'total_price' => $itemTotal,
                    'total_price_dollars' => $itemTotalUSD
                ]);
                
                $totalAmount += $itemTotal;
                $totalAmountUSD += $itemTotalUSD;
            }

            Log::info('Items de cargamento creados. Total PEN: ' . $totalAmount . ', Total USD: ' . $totalAmountUSD);

            $shipment->update([
                'total_amount' => $totalAmount,
                'total_amount_usd' => $totalAmountUSD
            ]);

            DB::commit();
            Log::info('Transacción completada exitosamente');

            return redirect()->route('shipments.index')->with('success', 'Cargamento creado correctamente. Total: S/. ' . number_format($totalAmount, 2) . ' / $ ' . number_format($totalAmountUSD, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear el cargamento: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al crear el cargamento: ' . $e->getMessage());
        }
    }

    public function edit(Shipment $shipment)
    {
        $stores = Store::all();
        $suppliers = Supplier::all();
        $shipment->load('items');
        
        return view('shipments.edit', compact('shipment', 'stores', 'suppliers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'arrival_date' => 'required|date',
            'store_id' => 'required|exists:stores,id',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.model' => 'nullable|string',
            'products.*.brand' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.currency' => 'required|in:PEN,USD',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $totalAmountUSD = 0;

            $shipment->update([
                'supplier_id' => $validated['supplier_id'],
                'store_id' => $validated['store_id'],
                'arrival_date' => $validated['arrival_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $shipment->items()->delete();

            foreach ($validated['products'] as $item) {
                $priceInSoles = $item['currency'] === 'PEN' ? $item['price'] : $this->currencyService->convertDollarsToSoles($item['price']);
                $priceInDollars = $item['currency'] === 'USD' ? $item['price'] : $this->currencyService->convertSolesToDollars($item['price']);
                $itemTotal = $item['quantity'] * $priceInSoles;
                $itemTotalUSD = $item['quantity'] * $priceInDollars;
                
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'name' => $item['name'],
                    'model' => $item['model'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $priceInSoles,
                    'unit_price_dollars' => $priceInDollars,
                    'total_price' => $itemTotal,
                    'total_price_dollars' => $itemTotalUSD
                ]);
                $totalAmount += $itemTotal;
                $totalAmountUSD += $itemTotalUSD;
            }

            $shipment->update(['total_amount' => $totalAmount, 'total_amount_usd' => $totalAmountUSD]);

            DB::commit();
            return redirect()->route('shipments.index')->with('success', 'Cargamento actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar el cargamento: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error al actualizar el cargamento: ' . $e->getMessage());
        }
    }

    public function show(Shipment $shipment)
    {
    $shipment->load(['store', 'supplier', 'items']);
    return view('shipments.show', compact('shipment'));
    }

    public function destroy(Shipment $shipment)
    {
        try {
            DB::beginTransaction();
            
            $shipment->items()->delete();
            $shipment->delete();
            
            DB::commit();
            return redirect()->route('shipments.index')->with('success', 'Cargamento eliminado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar el cargamento: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el cargamento: ' . $e->getMessage());
        }
    }
}

