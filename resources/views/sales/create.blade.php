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
                    <input type="text" id="cliente_nombre" name="cliente_nombre" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                    <input type="text" id="cliente_ruc" name="cliente_ruc" required pattern="\d{11}" class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                <select id="store_id" name="store_id" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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

        <div class="bg-gray-700/50 p-4 rounded-lg mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">Resumen de la Venta</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-300">Subtotal: S/. <span id="subtotal">0.00</span></p>
                </div>
                <div>
                    <p class="text-gray-300">IGV (18%): S/. <span id="igv">0.00</span></p>
                </div>
                <div>
                    <p class="text-gray-300 font-semibold">Total: S/. <span id="total">0.00</span></p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-200">
                Crear Venta
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsContainer = document.getElementById('products-container');
        const addProductButton = document.getElementById('add-product');
        const saleForm = document.getElementById('sale-form');
        let productIndex = 0;
    
        function calculateTotals() {
            let subtotal = 0;
            
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('.product-select');
                const quantity = row.querySelector('.quantity');
                const subtotalSpan = row.querySelector('.subtotal-amount');
                
                if (select.value && quantity.value) {
                    const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                    const itemSubtotal = price * parseInt(quantity.value);
                    subtotal += itemSubtotal;
                    subtotalSpan.textContent = itemSubtotal.toFixed(2);
                }
            });
    
            const igv = subtotal * 0.18;
            const total = subtotal + igv;
    
            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('igv').textContent = igv.toFixed(2);
            document.getElementById('total').textContent = total.toFixed(2);
        }
    
        function createProductRow() {
            const row = document.createElement('div');
            row.className = 'product-row bg-gray-700/50 p-4 rounded-lg space-y-4';
            row.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-5">
                        <select name="products[${productIndex}][id]" class="product-select w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="">-- Selecciona un producto --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-price="{{ $product->price_soles }}"
                                        data-name="{{ $product->name }}">
                                    {{ $product->code }} - {{ $product->name }} - S/. {{ number_format($product->price_soles, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <input type="number" name="products[${productIndex}][quantity]" class="quantity w-full bg-gray-700 text-white border border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Cantidad" min="1" value="1" required>
                    </div>
                    <div class="md:col-span-3">
                        <div class="text-gray-300">Subtotal: S/. <span class="subtotal-amount">0.00</span></div>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="button" class="remove-product bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition-colors duration-200">
                            Eliminar
                        </button>
                    </div>
                </div>
            `;
    
            productsContainer.appendChild(row);
    
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.quantity');
            const removeButton = row.querySelector('.remove-product');
    
            select.addEventListener('change', calculateTotals);
            quantity.addEventListener('input', calculateTotals);
            removeButton.addEventListener('click', function() {
                row.remove();
                calculateTotals();
            });
    
            calculateTotals();
            return row;
        }
    
        addProductButton.addEventListener('click', function() {
            createProductRow();
            productIndex++;
        });
    
        // Crear la primera fila de producto
        createProductRow();
        productIndex++;
    
        saleForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            
            try {
                const formData = new FormData(this);
                const products = [];
                
                document.querySelectorAll('.product-row').forEach(row => {
                    const select = row.querySelector('.product-select');
                    const quantity = row.querySelector('.quantity');
                    
                    if (select.value && quantity.value) {
                        products.push({
                            id: select.value,
                            quantity: quantity.value
                        });
                    }
                });
                
                formData.append('products', JSON.stringify(products));
    
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
    
                /// Error aquí 
                const data = await response.json();
    
                if (!response.ok) {
                    throw new Error(data.error || 'Error al procesar la venta');
                }
    
                if (data.success) {
                    alert('Venta creada exitosamente');
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.error || 'Error desconocido');
                }
    
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Ocurrió un error al procesar la venta');
            } finally {
                submitButton.disabled = false;
            }
        });
    });
    </script>
@endsection

