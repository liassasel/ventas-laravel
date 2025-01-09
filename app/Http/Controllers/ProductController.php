<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Inventory;
use App\Services\CurrencyConversionService;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    // Método privado para verificar permisos
    private function checkPermission()
    {
        if (!Auth::check() || (!Auth::user()->can_add_products && !Auth::user()->is_admin)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'mainStore'])->where('stock', '>', 0);
    
        if ($request->filled('store_id')) {
            $query->where('main_store_id', $request->store_id);
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    
        $query->orderBy('created_at', 'desc');
    
        $products = $query->paginate(10);
        $categories = Category::all();
        $stores = Store::all();
        $totalProducts = Product::count();
    
        return view('products.index', compact('products', 'categories', 'stores', 'totalProducts'));
    }

    public function create()
    {
        $this->checkPermission();
        
        $categories = Category::all();
        $stores = Store::all();
        return view('products.create', compact('categories', 'stores'));
    }

    public function store(Request $request)
    {
        $this->checkPermission();

        $validatedData = $request->validate([
            'code' => 'required|max:255',
            'serial' => 'required|string',
            'model' => 'nullable|max:255',
            'brand' => 'nullable|max:255',
            'color' => 'nullable|max:255',
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:PEN,USD',
            'stock' => 'required|integer|min:1',
            'main_store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        $serials = explode("\n", $validatedData['serial']);
        $serials = array_map('trim', $serials);
        $serials = array_filter($serials);
    
        $baseCode = $validatedData['code'];
        
        foreach ($serials as $index => $serial) {
            $uniqueCode = $baseCode . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            
            Product::create([
                'code' => $uniqueCode,
                'serial' => $serial,
                'model' => $validatedData['model'],
                'brand' => $validatedData['brand'],
                'color' => $validatedData['color'],
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price_dollars' => $validatedData['currency'] === 'USD'
                    ? $validatedData['price']
                    : $this->currencyService->convertSolesToDollars($validatedData['price']),
                'price_soles' => $validatedData['currency'] === 'PEN'
                    ? $validatedData['price']
                    : $this->currencyService->convertDollarsToSoles($validatedData['price']),
                'currency' => $validatedData['currency'],
                'stock' => 1,
                'main_store_id' => $validatedData['main_store_id'],
                'category_id' => $validatedData['category_id'],
                'status' => 1,
            ]);
        }
    
        return redirect()->route('products.index')->with('success', 'Products created successfully.');
    }

    public function edit(Product $product)
    {
        $this->checkPermission();

        $categories = Category::all();
        $stores = Store::all();
        return view('products.edit', compact('product', 'categories', 'stores'));
    }

    public function update(Request $request, Product $product)
    {
        $this->checkPermission();

        $validatedData = $request->validate([
            'code' => 'required|unique:products,code,' . $product->id . '|max:255',
            'name' => 'required|max:255',
            'serial' => 'nullable|max:255',
            'model' => 'nullable|max:255',
            'brand' => 'nullable|max:255',
            'color' => 'nullable|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:PEN,USD',
            'stock' => 'required|integer|min:0',
            'main_store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validatedData['currency'] === 'PEN') {
            $validatedData['price_soles'] = $validatedData['price'];
            $validatedData['price_dollars'] = $this->currencyService->convertSolesToDollars($validatedData['price']);
        } else {
            $validatedData['price_dollars'] = $validatedData['price'];
            $validatedData['price_soles'] = $this->currencyService->convertDollarsToSoles($validatedData['price']);
        }

        unset($validatedData['price']);

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->checkPermission();

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function import(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ProductsImport($this->currencyService), $request->file('file'));
            return redirect()->route('products.index')->with('success', 'Products imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }
}

