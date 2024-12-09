@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6">Edit Product</h1>
            <p class="mt-2 text-sm text-gray-400">Update the details of your product.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-white">
            Back to Products
        </a>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" class="mt-8">
        @csrf
        @method('PUT')
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <div class="space-y-6">
                <div>
                    <label for="code" class="block text-sm font-medium leading-6 text-white">Code</label>
                    <div class="mt-2">
                        <input type="text" name="code" id="code" value="{{ old('code', $product->code) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium leading-6 text-white">Category</label>
                    <div class="mt-2">
                        <select name="category_id" id="category_id" required
                                class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}" {{ $product->category_id == $child->id ? 'selected' : '' }}>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="price_soles" class="block text-sm font-medium leading-6 text-white">Price (Soles)</label>
                    <div class="mt-2">
                        <input type="number" name="price_soles" id="price_soles" value="{{ old('price_soles', $product->price_soles) }}" step="0.01" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium leading-6 text-white">Stock</label>
                    <div class="mt-2">
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required
                               class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-white">Description</label>
                    <div class="mt-2">
                        <textarea name="description" id="description" rows="4"
                                  class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('description', $product->description) }}</textarea>
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
                    Update Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

