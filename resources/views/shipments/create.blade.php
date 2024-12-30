@extends('layouts.app')

@section('title', 'Crear Cargamento')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">Crear Cargamento</h1>
        <a href="{{ route('shipments.index') }}" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">
            Volver a Cargamentos
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 mb-6 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="shipment-form" method="POST" action="{{ route('shipments.store') }}" class="bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
        @csrf
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Datos del Cargamento</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-300 mb-1">Número de Factura</label>
                    <input type="text" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber }}" readonly class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="arrival_date" class="block text-sm font-medium text-gray-300 mb-1">Fecha de Llegada</label>
                    <input type="date" id="arrival_date" name="arrival_date" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Notas</label>
            <textarea id="notes" name="notes" rows="3" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-200">
                Crear Cargamento
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsContainer = document.getElementById('products-container');
        const addProductButton = document.getElementById('add-product');
        let productIndex = 0;

        addProductButton.addEventListener('click', function() {
            const productRow = document.createElement('div');
            productRow.className = 'product-row grid grid-cols-1 md:grid-cols-4 gap-4 items-center';
            productRow.innerHTML = `
                <input type="text" name="products[${productIndex}][name]" class="bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Nombre del Producto">
                <input type="number" name="products[${productIndex}][quantity]" class="bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Cantidad" min="1">
                <input type="number" name="products[${productIndex}][price]" class="bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Precio" step="0.01" min="0">
                <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-200">
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
@endpush

