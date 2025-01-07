@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Ventas</h1>
            <p class="mt-2 text-sm text-gray-400">Lista de todas las ventas realizadas</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('sales.create') }}" 
               class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black 
                      transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 
                      focus-visible:outline-offset-2 focus-visible:outline-white">
                Nueva Venta
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mt-8 bg-gray-900/50 p-4 rounded-lg border border-white/10">
        <form action="{{ route('sales.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <div>
                    <label for="status" class="block text-sm font-medium text-white">Estado</label>
                    <select id="status" name="status" 
                            class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                        <option value="">Todos</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    </select>
                </div>
            </div>
            <div>
                <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Aplicar Filtros
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
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Tienda</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Usuario</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Total</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Estado</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Fecha</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($sales as $sale)
                        <tr class="hover:bg-white/5">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $sale->id }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $sale->store->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $sale->user->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                <a href="{{ route('sales.show', $sale) }}" class="text-indigo-400 hover:text-indigo-300">Ver Detalles</a>
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

