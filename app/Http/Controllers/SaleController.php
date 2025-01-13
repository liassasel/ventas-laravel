<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductSold;
use App\Models\Category;
use App\Models\Invoice;
use App\Services\GreenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice as GreenterInvoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\See;
use SunatEndpoints;


class SaleController extends Controller
{
    protected $greenterService;

    public function __construct(GreenterService $greenterService)
    {
        $this->greenterService = $greenterService;
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

    public function store(Request $request)
    {
        Log::info('Sale store method called', $request->all());

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
            // Verificar stock antes de crear la venta
            foreach ($validatedData['products'] as $productData) {
                $product = Product::find($productData['id']);
                if (!$product || $product->stock <= 0) {
                    throw new \Exception("El producto {$product->name} no tiene stock disponible.");
                }
            }

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
                
                // Actualizar el stock del producto
                $product->stock -= $productData['quantity'];
                $product->save();

                // Crear el item de venta
                $saleItem = $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $product->price_soles,
                ]);

                $totalAmount += $product->price_soles * $productData['quantity'];

                // Registrar el producto como vendido
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

            $sale->total_amount = $totalAmount;
            $sale->save();

            // Generar factura electrónica
            $result = $this->greenterService->generateInvoice($sale);

            if ($result->success) {
                // Guardar información de la factura
                $invoice = new Invoice([
                    'sale_id' => $sale->id,
                    'serie' => $result->serie,
                    'correlativo' => $result->correlativo,
                    'xml' => $result->xml,
                    'hash' => $result->hash,
                    'cdr' => $result->cdr,
                    'status' => 'emitida'
                ]);
                $invoice->save();

                DB::commit();
                Log::info('Sale and invoice created successfully', ['sale_id' => $sale->id]);

                return redirect()->route('sales.index')
                    ->with('success', 'Venta y factura electrónica completadas exitosamente.');
            } else {
                throw new \Exception("Error al generar factura electrónica: " . $result->error);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating sale and invoice', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    private function generateElectronicInvoice(Sale $sale)
    {
        $see = new See();
        $see->setCertificate(file_get_contents(storage_path('app/certificates/certificate.pem')));
        
        // Configurar el servicio (Producción o Beta)
        $see->setService(SunatEndpoints::FE_BETA);

        $company = new Company();
        $company->setRuc('20000000001')
            ->setRazonSocial('EMPRESA S.A.C.')
            ->setNombreComercial('EMPRESA')
            ->setAddress((new Address())
                ->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('NONE')
                ->setDireccion('AV LS 123'));

        $client = new Client();
        $client->setTipoDoc('6')
            ->setNumDoc($sale->client_ruc)
            ->setRznSocial($sale->client_name);

        $invoice = (new GreenterInvoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('01')
            ->setSerie('F001')
            ->setCorrelativo($sale->id)
            ->setFechaEmision(new \DateTime())
            ->setTipoMoneda('PEN')
            ->setClient($client)
            ->setMtoOperGravadas($sale->total_amount / 1.18)
            ->setMtoIGV($sale->total_amount - ($sale->total_amount / 1.18))
            ->setTotalImpuestos($sale->total_amount - ($sale->total_amount / 1.18))
            ->setValorVenta($sale->total_amount / 1.18)
            ->setMtoImpVenta($sale->total_amount)
            ->setCompany($company);

        $items = [];
        foreach ($sale->items as $item) {
            $items[] = (new SaleDetail())
                ->setCodProducto($item->product->code)
                ->setUnidad('NIU')
                ->setCantidad($item->quantity)
                ->setMtoValorUnitario($item->price / 1.18)
                ->setDescripcion($item->product->name)
                ->setMtoBaseIgv($item->price * $item->quantity / 1.18)
                ->setPorcentajeIgv(18)
                ->setIgv(($item->price * $item->quantity) - ($item->price * $item->quantity / 1.18))
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(($item->price * $item->quantity) - ($item->price * $item->quantity / 1.18))
                ->setMtoValorVenta($item->price * $item->quantity / 1.18)
                ->setMtoPrecioUnitario($item->price);
        }

        $invoice->setDetails($items);

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue('SON ' . $this->convertNumberToWords($sale->total_amount) . ' SOLES');

        $invoice->setLegends([$legend]);

        $result = $see->send($invoice);

        return $result;
    }

    private function convertNumberToWords($number)
    {
        // Implementa la lógica para convertir números a palabras
        // Puedes usar una librería o implementar tu propia función
        return "CIEN"; // Ejemplo simplificado
    }

    public function getProductsByStore(Request $request)
    {
        $storeId = $request->input('store_id');
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = Product::with('category')
            ->where('stock', '>', 0)
            ->where('main_store_id', $storeId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();
        return response()->json($products);
    }
}

