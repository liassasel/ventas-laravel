@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-semibold leading-tight mb-5">Dashboard de Ventas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-green-700 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2">Ventas Totales</h3>
                <p class="text-3xl font-bold">{{ $totalSales ?? 0 }}</p>
            </div>
            
            <div class=" rounded-lg bg-indigo-900 shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2">Productos</h3>
                <p class="text-3xl font-bold">{{ $totalProducts ?? 0 }}</p>
            </div>

            <div class=" rounded-lg bg-indigo-300 shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2">Usuarios</h3>
                <p class="text-3xl font-bold">{{ $totalUsers ?? 0 }}</p>
            </div>

            <div class=" bg-orange-400 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-2">Servicios Técnicos</h3>
                <p class="text-3xl font-bold">{{ $totalServices ?? 0 }}</p>
            </div>
        </div>

        @if(isset($settings))
        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold mb-4">Configuración del Sistema</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="system_start_time" class="block text-sm font-medium text-gray-200">Hora de Inicio</label>
                        <input type="time" name="system_start_time" id="system_start_time" 
                               value="{{ $settings->system_start_time }}" 
                               class="mt-1 block w-full rounded-md border-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="system_end_time" class="block text-sm font-medium text-gray-200">Hora de Fin</label>
                        <input type="time" name="system_end_time" id="system_end_time" 
                               value="{{ $settings->system_end_time }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_system_active" 
                               {{ $settings->is_system_active ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2">Sistema Activo</span>
                    </label>
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Actualizar Configuración
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

