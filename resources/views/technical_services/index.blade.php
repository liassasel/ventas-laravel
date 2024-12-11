@extends('layouts.app')

@section('title', 'Servicios Técnicos')

@section('content')
<div x-data="{ showModal: false, selectedService: null }">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Servicios Técnicos</h1>
            <p class="mt-2 text-sm text-gray-400">Lista de todos los servicios técnicos registrados.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0">
            <a href="{{ route('technical_services.create') }}" 
                class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                Agregar Servicio Técnico
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('technical_services.index') }}" method="GET" class="mt-4">
        <div class="flex flex-wrap gap-4">
            <div class="flex-grow">
                <label for="start_date" class="block text-sm font-medium text-gray-400">Fecha Inicio</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex-grow">
                <label for="end_date" class="block text-sm font-medium text-gray-400">Fecha Fin</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex-grow">
                <label for="seller_id" class="block text-sm font-medium text-gray-400">Vendedor</label>
                <select id="seller_id" name="seller_id" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todos los vendedores</option>
                    @foreach($sellers as $seller)
                        <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                            {{ $seller->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Buscar
                </button>
            </div>
        </div>
    </form>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <div class="overflow-hidden rounded-xl border border-white/10 bg-gray-900/50">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Nro de Guia</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Fecha de Orden</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Cliente</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Equipo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Vendedor</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Problema</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Precio</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado Reparación</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado Entrega</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 ">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($services as $service)
                            <tr class="hover:bg-white/5">
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->guide_number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->order_date->format('d/m/Y H:i') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">{{ $service->client_name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->brand }} {{ $service->model }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->seller->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">{{ $service->problem }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">${{ number_format($service->service_price, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    @switch($service->repair_status)
                                        @case('pending')
                                            <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-500 ring-1 ring-inset ring-yellow-400/30">Pendiente</span>
                                            @break
                                        @case('in_progress')
                                            <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">En Progreso</span>
                                            @break
                                        @case('repaired')
                                            <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Reparado</span>
                                            @break
                                        @case('unrepairable')
                                            <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/30">No Reparable</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    @if($service->delivery_status === 'delivered')
                                        <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">Entregado</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-500 ring-1 ring-inset ring-yellow-400/30">No Entregado</span>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <button @click="showModal = true; selectedService = {{ $service->toJson() }}" class="text-[#0070f3] hover:text-[#0761d1] mr-2">Ver</button>
                                    <a href="{{ route('technical_services.edit', $service->id) }}" class="text-[#0070f3] hover:text-[#0761d1]">Editar</a>
                                    <form action="{{ route('technical_services.destroy', $service->id) }}" method="POST" class="inline-block ml-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('¿Estás seguro de que quieres eliminar este servicio técnico?')">
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
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Detalles del Servicio Técnico
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400" x-text="'Cliente: ' + selectedService?.client_name"></p>
                            <p class="text-sm text-gray-400" x-text="'Teléfono: ' + selectedService?.client_phone"></p>
                            <p class="text-sm text-gray-400" x-text="'Equipo: ' + selectedService?.brand + ' ' + selectedService?.model"></p>
                            <p class="text-sm text-gray-400" x-text="'Número de Serie: ' + selectedService?.serial_number"></p>
                            <p class="text-sm text-gray-400" x-text="'Problema: ' + selectedService?.problem"></p>
                            <p class="text-sm text-gray-400" x-text="'Diagnóstico: ' + selectedService?.diagnosis"></p>
                            <p class="text-sm text-gray-400" x-text="'Solución: ' + (selectedService?.solution || 'No disponible')"></p>
                            <p class="text-sm text-gray-400" x-text="'Precio: $' + selectedService?.service_price"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm" @click="showModal = false">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

