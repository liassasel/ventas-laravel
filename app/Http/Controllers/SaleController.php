<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\ProductSold;
use App\Models\User;
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

    public function index(Request $request)
{
    $query = Sale::with(['store', 'user', 'items.product', 'invoice']);

    // Filtrar por fecha si se proporcionan los parámetros
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . ' 00:00:00',
            $request->end_date . ' 23:59:59'
        ]);
    }

    // Filtrar por estado si se proporciona
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Ordenar por fecha de creación descendente
    $query->orderBy('created_at', 'desc');

    // Paginar los resultados
    $sales = $query->paginate(10);

    // Obtener vendedores para el filtro
    $sellers = User::where('is_seller', true)->get();

    return view('sales.index', compact('sales', 'sellers'));
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

    try {
        $validatedData = $request->validate([
            'cliente_nombre' => 'required|string|max:255',
            'cliente_ruc' => 'required|string|size:11',
            'cliente_telefono' => 'nullable|string|max:20',
            'cliente_correo' => 'nullable|email|max:255',
            'cliente_dni' => 'nullable|string|max:8',
            'store_id' => 'required|exists:stores,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        // Crear la venta
        $sale = Sale::create([
            'client_name' => $validatedData['cliente_nombre'],
            'client_ruc' => $validatedData['cliente_ruc'],
            'client_phone' => $validatedData['cliente_telefono'],
            'client_email' => $validatedData['cliente_correo'],
            'client_dni' => $validatedData['cliente_dni'],
            'store_id' => $validatedData['store_id'],
            'user_id' => Auth::id(),
            'status' => 'completed'
        ]);

        $subtotal = 0;
        foreach ($validatedData['products'] as $productData) {
            $product = Product::findOrFail($productData['id']);
            
            if ($product->stock < $productData['quantity']) {
                throw new \Exception("Stock insuficiente para el producto: {$product->name}");
            }

            $itemSubtotal = $product->price_soles * $productData['quantity'];
            $subtotal += $itemSubtotal;

            // Crear item de venta
            $sale->items()->create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'price' => $product->price_soles,
                'subtotal' => $itemSubtotal,
                'is_service' => false
            ]);

            // Actualizar stock
            $product->decrement('stock', $productData['quantity']);
        }

        // Calcular IGV y total
        $igv = $subtotal * 0.18;
        $total = $subtotal + $igv;

        // Actualizar totales en la venta
        $sale->update([
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total_amount' => $total
        ]);

        // Generar factura electrónica
        $result = $this->greenterService->generateInvoice($sale);

        if (!$result->success) {
            throw new \Exception($result->error);
        }

        // Guardar la factura
        Invoice::create([
            'sale_id' => $sale->id,
            'serie' => $result->serie,
            'correlativo' => $result->correlativo,
            'xml' => $result->xml,
            'hash' => $result->hash,
            'cdr' => $result->cdr,
            'status' => 'emitida'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Venta creada exitosamente',
            'redirect' => route('sales.show', $sale->id)
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error en venta: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 422);
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

