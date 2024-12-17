@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-semibold leading-tight mb-5">Configuración del Sistema</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="system_start_time" class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
                        <input type="time" name="system_start_time" id="system_start_time" 
                               value="{{ old('system_start_time', $settings->system_start_time) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="system_end_time" class="block text-sm font-medium text-gray-700">Hora de Fin</label>
                        <input type="time" name="system_end_time" id="system_end_time" 
                               value="{{ old('system_end_time', $settings->system_end_time) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_system_active" 
                               value="1"
                               {{ old('is_system_active', $settings->is_system_active) ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2">Sistema Activo</span>
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Actualizar Configuración
                    </button>
                </div>
            </form>

            <div class="mt-8 border-t pt-6">
                <form action="{{ route('admin.settings.deactivate-non-admins') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                            onclick="return confirm('¿Está seguro de que desea desactivar todos los usuarios no administradores?')">
                        Desactivar Usuarios No Administradores
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

