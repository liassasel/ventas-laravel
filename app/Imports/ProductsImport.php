<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Store;
use App\Services\CurrencyConversionService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $currencyService;

    public function __construct(CurrencyConversionService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function model(array $row)
    {
        $category = Category::firstOrCreate(['name' => $row['categoria']]);
        $store = Store::firstOrCreate(
            ['name' => $row['tienda']],
            ['address' => $row['direccion_tienda'] ?? null]
        );

        $price = $row['precio'];
        $currency = strtoupper($row['moneda']);

        return new Product([
            'code' => $row['codigo'],
            'name' => $row['nombre'],
            'description' => $row['descripcion'] ?? null,
            'price_dollars' => $currency === 'USD' ? $price : $this->currencyService->convertSolesToDollars($price),
            'price_soles' => $currency === 'PEN' ? $price : $this->currencyService->convertDollarsToSoles($price),
            'stock' => 1,
            'category_id' => $category->id,
            'serial' => $row['serial'],
            'model' => $row['modelo'],
            'brand' => $row['marca'],
            'color' => $row['color'],
            'main_store_id' => $row['tienda'],
            'status' => 1
        ]);
    }

    public function rules(): array
    {
        return [
            'codigo' => 'required',
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'moneda' => 'required|in:USD,PEN',
            'serial' => 'required',
            'categoria' => 'required',
            'tienda' => 'required',
            'modelo' => 'required',
            'marca' => 'required',
            'color' => 'required',
        ];
    }
}

