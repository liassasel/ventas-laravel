@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6 text-white">Create Product</h1>
            <p class="mt-2 text-sm text-gray-400">Add a new product to your inventory.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-white">
            Back to Products
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

    <form action="{{ route('products.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="code" class="block text-sm font-medium leading-6 text-white">Code</label>
                    <div class="mt-2">
                        <input type="text" name="code" id="code" required value="{{ old('code') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter product code">
                    </div>
                </div>

                <div>
                    <label for="serial" class="block text-sm font-medium leading-6 text-white">Serial Numbers</label>
                    <div class="mt-2">
                        <textarea name="serial" id="serial" rows="5"
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                  placeholder="Enter one serial per line">{{ old('serial') }}</textarea>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter product name">
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium leading-6 text-white">Category</label>
                    <div class="mt-2">
                        <select name="category_id" id="category_id" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="price" class="block text-sm font-medium leading-6 text-white">Price</label>
                        <div class="mt-2">
                            <input type="number" name="price" id="price" step="0.01" required value="{{ old('price') }}"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium leading-6 text-white">Currency</label>
                        <div class="mt-2">
                            <select name="currency" id="currency" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="PEN" {{ old('currency') === 'PEN' ? 'selected' : '' }}>PEN</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium leading-6 text-white">Stock</label>
                    <div class="mt-2">
                        <input type="number" name="stock" id="stock" required value="{{ old('stock') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter stock quantity">
                    </div>
                </div>

                <div>
                    <label for="main_store_id" class="block text-sm font-medium leading-6 text-white">Main Store</label>
                    <div class="mt-2">
                        <select name="main_store_id" id="main_store_id" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="">Select a store</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('main_store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium leading-6 text-white">Model</label>
                    <div class="mt-2">
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter product model">
                    </div>
                </div>

                <div>
                    <label for="brand" class="block text-sm font-medium leading-6 text-white">Brand</label>
                    <div class="mt-2">
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter product brand">
                    </div>
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium leading-6 text-white">Color</label>
                    <div class="mt-2">
                        <input type="text" name="color" id="color" value="{{ old('color') }}"
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                               placeholder="Enter product color">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-white">Description</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                  placeholder="Enter product description">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-4">
                <a href="{{ route('products.index') }}"
                   class="rounded-md px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-black shadow-sm transition-all hover:bg-gray-200">
                    Create Product
                </button>

                {{-- dlskad;lkasd --}}
            </div>
        </div>
    </form>
</div>
@endsection

