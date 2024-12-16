@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-semibold leading-tight mb-5">Nueva Venta</h2>
        <form action="{{ route('sales.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="store_id">
                    Tienda
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="store_id" name="store_id" required>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="products">
                <div class="mb-4 product-row">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Producto
                    </label>
                    <div class="flex items-center">
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" name="products[0][id]" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - ${{ number_format($product->price, 2) }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="products[0][quantity]" placeholder="Cantidad" class="shadow appearance-none border rounded w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" required min="1">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="products[0][is_service]" class="form-checkbox h-5 w-5 text-gray-600">
                            <span class="ml-2 text-gray-700">Es servicio</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <button type="button" id="add-product" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Agregar Producto
                </button>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Crear Venta
                </button>
                <a href="{{ route('sales.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    let productCount = 1;
    const addProductButton = document.getElementById('add-product');
    const productsContainer = document.getElementById('products');

    addProductButton.addEventListener('click', function() {
    const newRow = document.createElement('div');
    newRow.className = 'mb-4 product-row';
    newRow.innerHTML = `
        <label class="block text-gray-700 text-sm font-bold mb-2">
            Producto
        </label>
        <div class="flex items-center">
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" name="products[${productCount}][id]" required>
                ${document.querySelector('select[name="products[0][id]"]').innerHTML}
            </select>
            <input type="number" name="products[${productCount}][quantity]" placeholder="Cantidad" class="shadow appearance-none border rounded w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" required min="1">
            <label class="inline-flex items-center">
                <input type="checkbox" name="products[${productCount}][is_service]" class="form-checkbox h-5 w-5 text-gray-600">
                <span class="ml-2 text-gray-700">Es servicio</span>
            </label>
        </div>
    `;
    productsContainer.appendChild(newRow);
    productCount++;
});
</script>
@endsection

