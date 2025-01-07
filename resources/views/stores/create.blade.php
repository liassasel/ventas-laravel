@extends('layouts.app')

@section('title', 'Crear Nueva Tienda')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Crear Nueva Tienda</h1>
            <p class="mt-2 text-sm text-gray-400">Añade una nueva tienda al sistema</p>
        </div>
    </div>

    <div class="mt-8 bg-gray-900/50 p-6 rounded-lg border border-white/10">
        <form action="{{ route('stores.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-white">
                        Nombre de la Tienda
                    </label>
                    <input type="text" id="name" name="name" required
                           class="mt-2 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-white">
                        Dirección
                    </label>
                    <input type="text" id="address" name="address" required
                           class="mt-2 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('stores.index') }}" 
                       class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Crear Tienda
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

