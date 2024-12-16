<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Models\Inventory;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(Request $request)
    {

        $query = Product::with('mainStore');

        if ($request->filled('store_id')) {
            $query->where('main_store_id', $request->store_id);
        }

        $stores = Store::all();



        $query = Product::with('category');

        // Filtrar por fecha si se proporciona
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Ordenar por fecha de creación (más reciente primero)
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        $products = $query->paginate(10);

        $categories = Category::all();
        return view('products.index', compact('products', 'categories', 'stores'));
    }

    public function create()
    {
        $categories = Category::all();
        $stores = Store::all();
        return view('products.create', compact('categories', 'stores',));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|unique:products|max:255',
            'serial' => 'required|array',
            'serial.*' => 'required|string|distinct',
            'model' => 'nullable|max:255',
            'brand' => 'nullable|max:255',
            'color' => 'nullable|max:255',
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:PEN,USD',
            'stock' => 'required|integer|min:0',
            'main_store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'code.unique' => 'The product code has already been registered.',
        ]);

        if ($validatedData['currency'] === 'PEN') {
            $validatedData['price_soles'] = $validatedData['price'];
            $validatedData['price_dollars'] = $this->currencyService->convertSolesToDollars($validatedData['price']);
        } else {
            $validatedData['price_dollars'] = $validatedData['price'];
            $validatedData['price_soles'] = $this->currencyService->convertDollarsToSoles($validatedData['price']);
        }

        unset($validatedData['price']);
        unset($validatedData['serials']);

        Product::create($validatedData);

        

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $stores = Store::all();
        $inventories = Inventory::where('product_id', $product->id)->get();
        return view('products.edit', compact('product', 'categories', 'stores', 'inventory'));
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
        ], [
            'code.unique' => 'The product code has already been registered.',
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

        $stores = Store::all();
            $serialIndex = 0;
            foreach ($stores as $store) {
                $quantity = $store->id == $request->input('main_store_id') ? count($request->input('serials')) : 0;
                $inventory = Inventory::create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                // Agregar seriales solo para la tienda principal
                if ($store->id == $request->input('main_store_id')) {
                    foreach ($request->input('serials') as $serial) {
                        $inventory->serials()->create(['serial_number' => $serial]);
                    }
                }
            }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
