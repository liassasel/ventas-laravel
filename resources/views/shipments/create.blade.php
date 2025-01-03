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
        <div class="mb-4 p-4 rounded-md bg-red-500/10 border border-red-500/50 text-red-400">
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
                <!-- Primera fila de producto -->
                <div class="product-row space-y-4 bg-gray-700/50 p-4 rounded-lg mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nombre del Producto</label>
                            <input type="text" name="products[0][name]" placeholder="Nombre" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Modelo</label>
                            <input type="text" name="products[0][model]" placeholder="Modelo (opcional)" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Marca</label>
                            <input type="text" name="products[0][brand]" placeholder="Marca (opcional)" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Cantidad</label>
                            <input type="number" name="products[0][quantity]" placeholder="Cantidad" value="1"
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                                   min="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Moneda</label>
                            <select name="products[0][currency]" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                                <option value="PEN">PEN</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Precio Unitario</label>
                            <input type="number" name="products[0][price]" placeholder="Precio" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                                   step="0.01" min="0" required>
                        </div>
                    </div>
                    <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md mt-2">
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


<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const addProductButton = document.getElementById('add-product');
    let productIndex = 1; // Empezamos en 1 porque ya tenemos el primer producto

    // Función para clonar una fila de producto
    function addProductRow() {
        const existingRow = productsContainer.querySelector('.product-row');
        const newRow = existingRow.cloneNode(true);
        
        // Actualizar los índices en los nombres de los campos
        newRow.querySelectorAll('input, select').forEach(input => {
            const name = input.name;
            if (name) {
                input.name = name.replace(/\[\d+\]/, `[${productIndex}]`);
                // Limpiar valores
                if (input.type === 'number') {
                    input.value = input.type === 'number' && input.name.includes('[quantity]') ? '1' : '';
                } else {
                    input.value = '';
                }
            }
        });

        // Añadir el nuevo row al contenedor
        productsContainer.appendChild(newRow);
        productIndex++;

        // Actualizar los event listeners de los botones de eliminar
        updateRemoveButtons();
    }

    // Función para actualizar los event listeners de los botones de eliminar
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-product');
        removeButtons.forEach(button => {
            button.onclick = function() {
                if (productsContainer.children.length > 1) {
                    this.closest('.product-row').remove();
                } else {
                    alert('Debe haber al menos un producto');
                }
            };
        });
    }

    // Inicializar los event listeners
    addProductButton.addEventListener('click', addProductRow);
    updateRemoveButtons();
});
</script>

@endsection

