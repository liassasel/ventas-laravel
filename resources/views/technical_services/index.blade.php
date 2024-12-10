@extends('layouts.app')

@section('title', 'Servicios Técnicos')

@section('content')
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

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
            <div class="overflow-hidden rounded-xl border border-white/10 bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-800">
    <thead class="bg-gray-900">
        <tr>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Cliente</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Equipo</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado Reparación</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado Entrega</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Vendedor</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Fecha de Orden</th>
            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Precio</th>
            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                <span class="sr-only">Acciones</span>
            </th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-800">
        @foreach($services as $service)
            <tr class="hover:bg-white/5">
                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">{{ $service->client_name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->brand }} {{ $service->model }}</td>
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
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->seller->name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $service->order_date->format('d/m/Y H:i') }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">${{ number_format($service->service_price, 2) }}</td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
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
@endsection

