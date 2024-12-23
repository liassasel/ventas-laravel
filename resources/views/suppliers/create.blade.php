@extends('layouts.app')

@section('title', 'Crear Proveedor')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Crear Proveedor</h1>
            <p class="mt-2 text-sm text-gray-400">Agrega un nuevo proveedor a tu lista.</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-400 hover:text-white">
            Volver a Proveedores
        </a>
    </div>

    @if ($errors->any())
        <div class="mt-4 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
            <ul class="list-disc list-inside text-sm text-red-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Nombre</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Ingrese el nombre del proveedor">
                    </div>
                </div>

                <div>
                    <label for="ruc_dni" class="block text-sm font-medium leading-6 text-white">RUC/DNI</label>
                    <div class="mt-2">
                        <input type="text" name="ruc_dni" id="ruc_dni" required value="{{ old('ruc_dni') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Ingrese el RUC o DNI">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium leading-6 text-white">Dirección</label>
                    <div class="mt-2">
                        <input type="text" name="address" id="address" required value="{{ old('address') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Ingrese la dirección">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium leading-6 text-white">Teléfono</label>
                    <div class="mt-2">
                        <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Ingrese el número de teléfono">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-white">Correo Electrónico</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Ingrese el correo electrónico">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-white">Descripción</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                  placeholder="Ingrese una descripción del proveedor">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('suppliers.index') }}"
                   class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-black shadow-sm transition-all hover:bg-gray-200">
                    Crear Proveedor
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

