@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-semibold leading-tight mb-5">Detalles de la Venta</h2>
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Información General</h3>
                <p><strong>ID de Venta:</strong> {{ $sale->id }}</p>
                <p><strong>Tienda:</strong> {{ $sale->store->name }}</p>
                <p><strong>Usuario:</strong> {{ $sale->user->name }}</p>
                <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong> {{ $sale->status }}</p>
                <p><strong>Total:</strong> ${{ number_format($sale->total_amount, 2) }}</p>
            </div>
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Productos</h3>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cantidad
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Precio Unitario
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Subtotal
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tipo
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $item->product->name }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $item->quantity }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">${{ number_format($item->price, 2) }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">${{ number_format($item->quantity * $item->price, 2) }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $item->is_service ? 'Servicio' : 'Producto' }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($sale->invoice)
            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">Información de Factura</h3>
                <p><strong>Número de Factura:</strong> {{ $sale->invoice->invoice_number }}</p>
                <p><strong>Estado de Factura:</strong> {{ $sale->invoice->status }}</p>
            </div>
            @endif
            <div class="flex items-center justify-between">
                <a href="{{ route('sales.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Volver a la lista de ventas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

