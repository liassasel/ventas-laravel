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
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Cantidad</label>
                            <input type="number" name="products[0][quantity]" placeholder="Cantidad" 
                                   class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                                   min="1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Moneda</label>
                            <select name="products[0][currency]" class="currency-select w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                                <option value="PEN">PEN</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Precio Unitario</label>
                            <input type="number" name="products[0][price]" placeholder="Precio" 
                                   class="price-input w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                                   step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Precio en PEN</label>
                            <div class="price-pen w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm px-3 py-2">
                                S/. 0.00
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Precio en USD</label>
                            <div class="price-usd w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm px-3 py-2">
                                $ 0.00
                            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('products-container');
    const addProductButton = document.getElementById('add-product');

    let productIndex = 1;

    function updatePrices(row) {
        const priceInput = row.querySelector('.price-input');
        const currencySelect = row.querySelector('.currency-select');
        const penDisplay = row.querySelector('.price-pen');
        const usdDisplay = row.querySelector('.price-usd');

        const price = parseFloat(priceInput.value) || 0;
        const currency = currencySelect.value;

        if (currency === 'PEN') {
            penDisplay.textContent = `S/. ${price.toFixed(2)}`;
            fetch(`/api/convert-currency?amount=${price}&from=PEN&to=USD`)
                .then(response => response.json())
                .then(data => {
                    usdDisplay.textContent = `$ ${data.convertedAmount.toFixed(2)}`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    usdDisplay.textContent = 'Error';
                });
        } else {
            usdDisplay.textContent = `$ ${price.toFixed(2)}`;
            fetch(`/api/convert-currency?amount=${price}&from=USD&to=PEN`)
                .then(response => response.json())
                .then(data => {
                    penDisplay.textContentpenDisplay.textContent = `S/. ${data.convertedAmount.toFixed(2)}`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    penDisplay.textContent = 'Error';
                });
        }
    }

    function addProductRow() {
        const newRow = document.createElement('div');
        newRow.className = 'product-row space-y-4 bg-gray-700/50 p-4 rounded-lg mb-4';
        
        newRow.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nombre del Producto</label>
                    <input type="text" name="products[${productIndex}][name]" placeholder="Nombre" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Modelo</label>
                    <input type="text" name="products[${productIndex}][model]" placeholder="Modelo (opcional)" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Marca</label>
                    <input type="text" name="products[${productIndex}][brand]" placeholder="Marca (opcional)" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Cantidad</label>
                    <input type="number" name="products[${productIndex}][quantity]" placeholder="Cantidad" 
                           class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                           min="1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Moneda</label>
                    <select name="products[${productIndex}][currency]" class="currency-select w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" required>
                        <option value="PEN">PEN</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Precio Unitario</label>
                    <input type="number" name="products[${productIndex}][price]" placeholder="Precio" 
                           class="price-input w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm" 
                           step="0.01" min="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Precio en PEN</label>
                    <div class="price-pen w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm px-3 py-2">
                        S/. 0.00
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Precio en USD</label>
                    <div class="price-usd w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm px-3 py-2">
                        $ 0.00
                    </div>
                </div>
            </div>
            <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md mt-2">
                Eliminar
            </button>
        `;

        productsContainer.appendChild(newRow);
        
        const priceInput = newRow.querySelector('.price-input');
        const currencySelect = newRow.querySelector('.currency-select');
        
        priceInput.addEventListener('input', () => updatePrices(newRow));
        currencySelect.addEventListener('change', () => updatePrices(newRow));

        newRow.querySelector('.remove-product').addEventListener('click', function() {
            if (productsContainer.children.length > 1) {
                newRow.remove();
            } else {
                alert('Debe haber al menos un producto');
            }
        });

        productIndex++;
    }

    document.querySelectorAll('.product-row').forEach(row => {
        const priceInput = row.querySelector('.price-input');
        const currencySelect = row.querySelector('.currency-select');
        
        priceInput.addEventListener('input', () => updatePrices(row));
        currencySelect.addEventListener('change', () => updatePrices(row));

        row.querySelector('.remove-product').addEventListener('click', function(e) {
            if (productsContainer.children.length > 1) {
                e.target.closest('.product-row').remove();
            } else {
                alert('Debe haber al menos un producto');
            }
        });
    });

    addProductButton.addEventListener('click', addProductRow);
});
</script>
@endpush
@endsection

