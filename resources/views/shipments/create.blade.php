@extends('layouts.app')

@section('title', 'Crear Cargamento')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">Crear Cargamento</h1>
        <a href="{{ route('shipments.index') }}" class="text-blue-500 hover:text-blue-600">
            Volver a Cargamentos
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 rounded-md bg-red-500 text-white">
            {{ session('error') }}
        </div>
    @endif

    <form id="shipmentForm" action="{{ route('shipments.store') }}" method="POST" class="bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Número de Factura</label>
                <input type="text" name="invoice_number" value="{{ $invoiceNumber }}" readonly 
                       class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Proveedor</label>
                <select name="supplier_id" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                    <option value="">Seleccionar proveedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Fecha de Llegada</label>
                <input type="date" name="arrival_date" required
                       class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Tienda</label>
                <select name="store_id" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                    <option value="">Seleccionar tienda</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Productos</h2>
            <div id="products-container">
                <div class="product-row grid grid-cols-1 md:grid-cols-4 gap-4 items-center mb-4">
                    <input type="text" name="products[0][name]" placeholder="Nombre del Producto" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                    <input type="number" name="products[0][quantity]" placeholder="Cantidad" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                           min="1" required>
                    <input type="number" name="products[0][unit_price]" placeholder="Precio Unitario" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                           step="0.01" min="0" required>
                    <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Eliminar
                    </button>
                </div>
            </div>
            <button type="button" id="add-product" 
                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Agregar Producto
            </button>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Notas</label>
            <textarea name="notes" rows="3" 
                      class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md">
                Crear Cargamento
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const addProductButton = document.getElementById('add-product');

    let productIndex = 1;

    function addProductRow() {
        const newRow = document.createElement('div');
        newRow.className = 'product-row grid grid-cols-1 md:grid-cols-4 gap-4 items-center mb-4';
        
        newRow.innerHTML = `
            <input type="text" name="products[${productIndex}][name]" placeholder="Nombre del Producto" 
                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
            <input type="number" name="products[${productIndex}][quantity]" placeholder="Cantidad" 
                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                   min="1" required>
            <input type="number" name="products[${productIndex}][unit_price]" placeholder="Precio Unitario" 
                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                   step="0.01" min="0" required>
            <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                Eliminar
            </button>
        `;

        productsContainer.appendChild(newRow);
        productIndex++;

        newRow.querySelector('.remove-product').addEventListener('click', function() {
            newRow.remove();
        });
    }

    addProductButton.addEventListener('click', addProductRow);

    // Eliminar producto inicial
    document.querySelector('.remove-product').addEventListener('click', function(e) {
        if (productsContainer.children.length > 1) {
            e.target.closest('.product-row').remove();
        } else {
            alert('Debe haber al menos un producto');
        }
    });
});
</script>
@endpush
@endsection

