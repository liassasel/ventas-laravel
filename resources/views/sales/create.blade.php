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
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cliente_nombre">
                    Nombre del Cliente
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cliente_nombre" name="cliente_nombre" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cliente_telefono">
                    Teléfono del Cliente
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cliente_telefono" name="cliente_telefono">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cliente_correo">
                    Correo del Cliente
                </label>
                <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cliente_correo" name="cliente_correo">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cliente_ruc">
                    RUC del Cliente
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cliente_ruc" name="cliente_ruc">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="cliente_dni">
                    DNI del Cliente
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cliente_dni" name="cliente_dni">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="numero_guia">
                    Número de Guía
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="numero_guia" name="numero_guia" value="{{ $numeroGuia }}" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="product_search">
                    Buscar Producto
                </label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="product_search" placeholder="Buscar producto...">
            </div>
            <div id="products">
                <!-- Los productos se agregarán aquí dinámicamente -->
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
    let productCount = 0;
    const addProductButton = document.getElementById('add-product');
    const productsContainer = document.getElementById('products');
    const storeSelect = document.getElementById('store_id');
    const productSearch = document.getElementById('product_search');

    storeSelect.addEventListener('change', fetchProducts);
    productSearch.addEventListener('input', fetchProducts);

    function fetchProducts() {
        const storeId = storeSelect.value;
        const search = productSearch.value;

        fetch(`/sales/get-products-by-store?store_id=${storeId}&search=${search}`)
            .then(response => response.json())
            .then(products => {
                updateProductDropdown(products);
            });
    }

    function updateProductDropdown(products) {
        const productDropdowns = document.querySelectorAll('select[name^="products["][name$="[id]"]');
        productDropdowns.forEach(dropdown => {
            dropdown.innerHTML = '';
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} - ${product.name} - $${product.price_soles.toFixed(2)}`;
                dropdown.appendChild(option);
            });
        });
    }

    addProductButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'mb-4 product-row';
        newRow.innerHTML = `
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Producto
            </label>
            <div class="flex items-center">
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2" name="products[${productCount}][id]" required>
                    <!-- Options will be populated dynamically -->
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
        fetchProducts();
    });

    // Initial fetch of products
    fetchProducts();
</script>
@endsection

