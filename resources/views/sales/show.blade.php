@extends('layouts.app')

@section('title', 'Detalles de Venta')

@section('content')
<div class="container mx-auto px-4 sm:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">Detalles de la Venta</h1>
        <a href="{{ route('sales.index') }}" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">
            Volver a Ventas
        </a>
    </div>
    <div class="bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Información General</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-300"><span class="font-medium">ID de Venta:</span> {{ $sale->id }}</p>
                <p class="text-gray-300"><span class="font-medium">Tienda:</span> {{ $sale->store->name }}</p>
                <p class="text-gray-300"><span class="font-medium">Usuario:</span> {{ $sale->user->name }}</p>
                <p class="text-gray-300"><span class="font-medium">Fecha:</span> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-gray-300"><span class="font-medium">Estado:</span> {{ ucfirst($sale->status) }}</p>
                <p class="text-gray-300"><span class="font-medium">Total:</span> ${{ number_format($sale->total_amount, 2) }}</p>
            </div>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Productos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Precio Unitario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $item->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">${{ number_format($item->quantity * $item->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $item->is_service ? 'Servicio' : 'Producto' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($sale->invoice)
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Información de Factura</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-300"><span class="font-medium">Número de Factura:</span> {{ $sale->invoice->invoice_number }}</p>
                <p class="text-gray-300"><span class="font-medium">Estado de Factura:</span> {{ ucfirst($sale->invoice->status) }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

