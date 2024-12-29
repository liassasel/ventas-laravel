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

class ProductController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'mainStore']);
    
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
    
        return view('products.index', compact('products', 'categories', 'stores'));
    }

    public function create()
    {
        $categories = Category::all();
        $stores = Store::all();
        return view('products.create', compact('categories', 'stores'));
    }

    public function store(Request $request)
    {
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
        $categories = Category::all();
        $stores = Store::all();
        return view('products.edit', compact('product', 'categories', 'stores'));
    }

    public function update(Request $request, Product $product)
    {
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
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function import(Request $request)
    {
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

