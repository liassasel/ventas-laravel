@extends('layouts.app')

@section('title', 'Detalle de Cargamento')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-white">Detalle de Cargamento</h1>
            <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-400 hover:text-white">
                Volver a Proveedores
            </a>
        </div>

        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-medium text-white mb-2">Información del Proveedor</h2>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-400">Nombre:</dt>
                            <dd class="text-white">{{ $supplier->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">RUC/DNI:</dt>
                            <dd class="text-white">{{ $supplier->ruc_dni }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h2 class="text-lg font-medium text-white mb-2">Información del Cargamento</h2>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-400">Número de Factura:</dt>
                            <dd class="text-white">{{ $shipment->invoice_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Fecha de Llegada:</dt>
                            <dd class="text-white">{{ $shipment->arrival_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-400">Total:</dt>
                            <dd class="text-white">S/. {{ number_format($shipment->total_amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($shipment->notes)
                <div>
                    <h2 class="text-lg font-medium text-white mb-2">Notas</h2>
                    <p class="text-gray-300">{{ $shipment->notes }}</p>
                </div>
            @endif

            <div>
                <h2 class="text-lg font-medium text-white mb-4">Productos del Cargamento</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Producto</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Cantidad</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Precio Unitario</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($shipment->items as $item)
                                <tr class="hover:bg-white/5">
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                        {{ $item->product->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                        S/. {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-white">
                                        S/. {{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-900">
                                <td colspan="3" class="px-3 py-3.5 text-right text-sm font-semibold text-white">Total:</td>
                                <td class="px-3 py-3.5 text-left text-sm font-semibold text-white">
                                    S/. {{ number_format($shipment->total_amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

