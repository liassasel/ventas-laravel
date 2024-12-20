<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Crear Venta</h1>
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="sale-form" method="POST" action="{{ route('sales.store') }}">
            @csrf
            <!-- Datos del Cliente -->
            <div class="mb-4">
                <label for="cliente_nombre" class="block text-sm font-medium text-gray-700">Nombre del Cliente</label>
                <input type="text" id="cliente_nombre" name="cliente_nombre" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cliente_telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" id="cliente_telefono" name="cliente_telefono" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cliente_correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" id="cliente_correo" name="cliente_correo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cliente_ruc" class="block text-sm font-medium text-gray-700">RUC</label>
                <input type="text" id="cliente_ruc" name="cliente_ruc" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="cliente_dni" class="block text-sm font-medium text-gray-700">DNI</label>
                <input type="text" id="cliente_dni" name="cliente_dni" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Selección de Tienda -->
            <div class="mb-4">
                <label for="store_id" class="block text-sm font-medium text-gray-700">Seleccionar Tienda</label>
                <select id="store_id" name="store_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">-- Selecciona una tienda --</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Contenedor de Productos -->
            <div id="products-container" class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Productos</label>
                <button type="button" id="add-product" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">Añadir Producto</button>
            </div>

            <!-- Botón de Crear Venta -->
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Crear Venta</button>
        </form>
    </div>

    <template id="product-row-template">
        <div class="product-row flex space-x-4 items-center mt-4">
            <select name="products[0][id]" class="product-select border-gray-300 rounded-md shadow-sm flex-1">
                <option value="">-- Selecciona un producto --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" name="products[0][quantity]" class="border-gray-300 rounded-md shadow-sm flex-1" placeholder="Cantidad" min="1">
            <button type="button" class="remove-product bg-red-500 text-white px-2 py-1 rounded-md">Eliminar</button>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productsContainer = document.getElementById('products-container');
            const addProductButton = document.getElementById('add-product');
            const productTemplate = document.getElementById('product-row-template').content;

            let productIndex = 0;

            // Añadir una fila de producto
            addProductButton.addEventListener('click', () => {
                const newRow = document.importNode(productTemplate, true);

                // Actualizar los índices de los campos dinámicos
                newRow.querySelector('select').name = `products[${productIndex}][id]`;
                newRow.querySelector('input').name = `products[${productIndex}][quantity]`;

                productsContainer.appendChild(newRow);
                attachRemoveEvent();
                productIndex++;
            });

            // Eliminar una fila de producto
            const attachRemoveEvent = () => {
                document.querySelectorAll('.remove-product').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.target.closest('.product-row').remove();
                    });
                });
            };
        });
    </script>
</body>
</html>
