@extends('layouts.app')

@section('title', 'Crear Venta')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">Crear Venta</h1>
        <a href="{{ route('sales.index') }}" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">
            Volver a Ventas
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/50 text-red-700 p-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="sale-form" method="POST" action="{{ route('sales.store') }}" class="bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
        @csrf
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Datos del Cliente</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cliente_nombre" class="block text-sm font-medium text-gray-300 mb-1">Nombre del Cliente</label>
                    <input type="text" id="cliente_nombre" name="cliente_nombre" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="cliente_telefono" class="block text-sm font-medium text-gray-300 mb-1">Teléfono</label>
                    <input type="text" id="cliente_telefono" name="cliente_telefono" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="cliente_correo" class="block text-sm font-medium text-gray-300 mb-1">Correo Electrónico</label>
                    <input type="email" id="cliente_correo" name="cliente_correo" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="cliente_ruc" class="block text-sm font-medium text-gray-300 mb-1">RUC</label>
                    <input type="text" id="cliente_ruc" name="cliente_ruc" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="cliente_dni" class="block text-sm font-medium text-gray-300 mb-1">DNI</label>
                    <input type="text" id="cliente_dni" name="cliente_dni" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Selección de Tienda</h2>
            <div>
                <label for="store_id" class="block text-sm font-medium text-gray-300 mb-1">Seleccionar Tienda</label>
                <select id="store_id" name="store_id" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">-- Selecciona una tienda --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Productos</h2>
            <div id="products-container" class="space-y-4">
                <!-- Product rows will be added here dynamically -->
            </div>
            <button type="button" id="add-product" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                Añadir Producto
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-200">
                Crear Venta
            </button>
        </div>
    </form>
</div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsContainer = document.getElementById('products-container');
        const addProductButton = document.getElementById('add-product');
        let productIndex = 0;

        addProductButton.addEventListener('click', function() {
            const productRow = document.createElement('div');
            productRow.className = 'product-row bg-gray-700/50 p-4 rounded-lg flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-2';
            productRow.innerHTML = `
                <select name="products[${productIndex}][id]" class="flex-grow bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mb-2 md:mb-0">
                    <option value="">-- Selecciona un producto --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productIndex}][quantity]" class="w-full md:w-24 bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mb-2 md:mb-0" placeholder="Cantidad" min="1">
                <button type="button" class="remove-product w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-200">
                    Eliminar
                </button>
            `;

            productsContainer.appendChild(productRow);

            const removeButton = productRow.querySelector('.remove-product');
            removeButton.addEventListener('click', function() {
                productRow.remove();
            });

            productIndex++;
        });
    });
</script>


