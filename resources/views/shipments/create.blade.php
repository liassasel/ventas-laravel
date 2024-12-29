@extends('layouts.app')

@section('title', 'Nuevo Cargamento')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-white">Nuevo Cargamento</h1>
            <a href="{{ route('shipments.index') }}" class="text-sm text-gray-400 hover:text-white">
                Volver a Cargamentos
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                <ul class="list-disc list-inside text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('shipments.store') }}" method="POST" id="shipmentForm">
            @csrf
            <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6 space-y-6">
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-white">Proveedor</label>
                    <select name="supplier_id" id="supplier_id" required
                            class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                        <option value="">Seleccionar Proveedor</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-white">Número de Factura</label>
                    <input type="text" name="invoice_number" id="invoice_number" required 
                           value="{{ $invoiceNumber }}" readonly
                           class="mt-1 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 cursor-not-allowed">
                </div>

                <div>
                    <label for="arrival_date" class="block text-sm font-medium text-white">Fecha de Llegada</label>
                    <input type="date" name="arrival_date" id="arrival_date" required value="{{ old('arrival_date') }}"
                           class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-white">Notas</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <h2 class="text-lg font-medium text-white mb-2">Productos del Cargamento</h2>
                    <div id="products-container">
                        <!-- Los productos se agregarán aquí dinámicamente -->
                    </div>
                    <button type="button" id="add-product" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                        Agregar Producto
                    </button>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-white mb-2">Total del Cargamento: <span id="total-amount">S/. 0.00</span></h3>
                </div>

                <div class="flex justify-end gap-x-4">
                    <a href="{{ route('shipments.index') }}"
                       class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Guardar Cargamento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let productCount = 0;

    function addProduct() {
        const container = document.getElementById('products-container');
        const productDiv = document.createElement('div');
        productDiv.className = 'product-item mb-4 p-4 border border-gray-700 rounded-md';
        productDiv.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white">Producto</label>
                    <select name="products[${productCount}][product_id]" required class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                        <option value="">Seleccionar Producto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Cantidad</label>
                    <input type="number" name="products[${productCount}][quantity]" required min="1" class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white">Precio Unitario</label>
                    <input type="number" name="products[${productCount}][unit_price]" required min="0" step="0.01" class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeProduct(this)" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition ease-in-out duration-150">
                        Eliminar
                    </button>
                </div>
            </div>
        `;
        container.appendChild(productDiv);
        productCount++;
        updateTotal();
    }

    function removeProduct(button) {
        button.closest('.product-item').remove();
        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.product-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('input[name$="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(item.querySelector('input[name$="[unit_price]"]').value) || 0;
            total += quantity * unitPrice;
        });
        document.getElementById('total-amount').textContent = `S/. ${total.toFixed(2)}`;
    }

    document.getElementById('add-product').addEventListener('click', addProduct);
    document.getElementById('products-container').addEventListener('input', updateTotal);
    document.getElementById('shipmentForm').addEventListener('submit', function(e) {
        const products = document.querySelectorAll('.product-item');
        if (products.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto al cargamento.');
        }
    });

    // Agregar un producto por defecto cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        addProduct();
    });
</script>
@endpush

