@extends('layouts.app')

@section('title', 'Crear Venta')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <h1 class="text-2xl font-semibold text-white mb-6">Crear Nueva Venta</h1>

    <form action="{{ route('sales.store') }}" method="POST" class="bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        
        <!-- Cliente Information -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="cliente_nombre">
                    Nombre del Cliente
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="cliente_nombre" 
                       type="text" 
                       name="cliente_nombre" 
                       required>
            </div>
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="cliente_telefono">
                    Teléfono
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="cliente_telefono" 
                       type="text" 
                       name="cliente_telefono">
            </div>
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="cliente_correo">
                    Correo
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="cliente_correo" 
                       type="email" 
                       name="cliente_correo">
            </div>
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="cliente_dni">
                    DNI
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="cliente_dni" 
                       type="text" 
                       name="cliente_dni">
            </div>
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="cliente_ruc">
                    RUC
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="cliente_ruc" 
                       type="text" 
                       name="cliente_ruc">
            </div>
            <div>
                <label class="block text-white text-sm font-bold mb-2" for="store_id">
                    Tienda
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="store_id" 
                        name="store_id" 
                        required>
                    <option value="">Seleccionar Tienda</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Products Section -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <h2 class="text-xl font-semibold text-white">Productos</h2>
                <button type="button" id="add-product" class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Agregar Producto
                </button>
            </div>

            <!-- Product Search -->
            <div class="mb-4">
                <input type="text" 
                       id="product-search" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       placeholder="Buscar productos...">
            </div>

            <!-- Category Filter -->
            <div class="mb-4">
                <select id="category-filter" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="products-container" class="space-y-4">
                <!-- Products will be added here dynamically -->
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="submit">
                Crear Venta
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addProductButton = document.getElementById('add-product');
    const productsContainer = document.getElementById('products-container');
    const productSearch = document.getElementById('product-search');
    const categoryFilter = document.getElementById('category-filter');
    const storeSelect = document.getElementById('store_id');
    let products = @json($products);

    function filterProducts(searchTerm, categoryId, storeId) {
        return products.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                                product.code.toLowerCase().includes(searchTerm.toLowerCase());
            const matchesCategory = !categoryId || product.category_id == categoryId;
            const matchesStore = !storeId || product.store_id == storeId;
            return matchesSearch && matchesCategory && matchesStore && product.stock > 0;
        });
    }

    function updateProductOptions(select) {
        const searchTerm = productSearch.value;
        const categoryId = categoryFilter.value;
        const storeId = storeSelect.all;
        const filteredProducts = filterProducts(searchTerm, categoryId, storeId);

        select.innerHTML = '<option value="">Seleccionar Producto</option>';
        filteredProducts.forEach(product => {
            select.innerHTML += `
                <option value="${product.id}">
                    ${product.name} - S/. ${product.price_soles} (Stock: ${product.stock})
                </option>
            `;
        });
    }

    function createProductRow() {
        const row = document.createElement('div');
        row.className = 'flex items-center space-x-4';
        row.innerHTML = `
            <select name="products[]" class="flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Seleccionar Producto</option>
            </select>
            <input type="number" name="quantities[]" class="w-24 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Cantidad" required min="1">
            <button type="button" class="remove-product bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Eliminar
            </button>
        `;

        const select = row.querySelector('select');
        updateProductOptions(select);

        row.querySelector('.remove-product').addEventListener('click', function() {
            row.remove();
        });

        return row;
    }

    addProductButton.addEventListener('click', function() {
        productsContainer.appendChild(createProductRow());
    });

    [productSearch, categoryFilter, storeSelect].forEach(element => {
        element.addEventListener('change', function() {
            document.querySelectorAll('#products-container select').forEach(select => {
                updateProductOptions(select);
            });
        });
    });

    // Add initial product row
    productsContainer.appendChild(createProductRow());
});
</script>
@endsection

