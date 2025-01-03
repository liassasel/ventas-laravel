@extends('layouts.app')

@section('title', 'Ver Cargamento')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">Detalles del Cargamento</h1>
        <div class="space-x-4">
            <a href="{{ route('shipments.edit', $shipment) }}" class="text-blue-500 hover:text-blue-600">
                Editar
            </a>
            <a href="{{ route('shipments.index') }}" class="text-gray-400 hover:text-gray-300">
                Volver a Cargamentos
            </a>
        </div>
    </div>

    <div class="bg-gray-800 shadow-md rounded-lg p-6 space-y-6">
        <!-- Información General -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-white mb-4">Información General</h2>
                <dl class="grid grid-cols-1 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Número de Factura</dt>
                        <dd class="text-white">{{ $shipment->invoice_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Proveedor</dt>
                        <dd class="text-white">{{ $shipment->supplier->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Tienda</dt>
                        <dd class="text-white">{{ $shipment->store->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Fecha de Llegada</dt>
                        <dd class="text-white">{{ $shipment->arrival_date->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-white mb-4">Totales</h2>
                <dl class="grid grid-cols-1 gap-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Total en Soles</dt>
                        <dd class="text-white">S/. {{ number_format($shipment->total_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Total en Dólares</dt>
                        <dd class="text-white">$ {{ number_format($shipment->total_amount_usd, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-400">Estado</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($shipment->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Lista de Productos -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-white mb-4">Productos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Marca</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Precio Unit. (PEN)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Precio Unit. (USD)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total (PEN)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total (USD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($shipment->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $item->model ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $item->brand ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">S/. {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">$ {{ number_format($item->unit_price_dollars, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">S/. {{ number_format($item->total_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">$ {{ number_format($item->total_price_dollars, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($shipment->notes)
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-white mb-4">Notas</h2>
                <p class="text-gray-300">{{ $shipment->notes }}</p>
            </div>
        @endif
    </div>
</div>
@endsection

