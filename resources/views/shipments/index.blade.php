@extends('layouts.app')

@section('title', 'Cargamentos')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Cargamentos</h1>
            <p class="mt-2 text-sm text-gray-400">Lista de todos los cargamentos registrados</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('shipments.create') }}" 
               class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black 
                      transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 
                      focus-visible:outline-offset-2 focus-visible:outline-white">
                Agregar Cargamento
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-8 bg-gray-900/50 p-4 rounded-lg border border-white/10">
        <form action="{{ route('shipments.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-white">Fecha Inicio</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                           class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-white">Fecha Fin</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                           class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
            </div>
            <div>
                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Factura</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Proveedor</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Fecha</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Productos</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Total (S/.)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Total ($)</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($shipments as $shipment)
                            <tr class="hover:bg-white/5">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">
                                    {{ $shipment->invoice_number }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ $shipment->supplier->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ $shipment->arrival_date->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ $shipment->items_count ?? $shipment->items->count() }} productos
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    S/. {{ number_format($shipment->total_amount, 2) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    $ {{ number_format($shipment->total_amount_usd, 2) }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('shipments.show', $shipment) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 mr-3">
                                        Ver
                                    </a>
                                    <a href="{{ route('shipments.edit', $shipment) }}" 
                                       class="text-[#0070f3] hover:text-[#0761d1] mr-3">
                                        Editar
                                    </a>
                                    <form action="{{ route('shipments.destroy', $shipment) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" 
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este cargamento?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6">
        {{ $shipments->links() }}
    </div>
</div>
@endsection

