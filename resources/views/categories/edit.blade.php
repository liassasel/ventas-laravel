@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold leading-tight text-white">Editar Categoría</h1>
            <a href="{{ route('categories.index') }}" 
               class="text-sm text-gray-400 hover:text-white">
                Volver a Categorías
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
                <ul class="list-disc list-inside text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-white">
                            Nombre de la Categoría
                        </label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $category->name) }}"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-white">
                            Categoría Padre (Opcional)
                        </label>
                        <div class="mt-2">
                            <select name="parent_id" id="parent_id"
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="">Ninguna</option>
                                @foreach($categories as $cat)
                                    @if($cat->id !== $category->id)
                                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-x-4">
                        <a href="{{ route('categories.index') }}"
                           class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Actualizar Categoría
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

