@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold leading-6">Create Product</h1>
            <p class="mt-2 text-sm text-gray-400">Add a new product to your inventory.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm text-gray-400 hover:text-white">
            Back to Products
        </a>
    </div>

    <form action="{{ route('products.store') }}" method="POST" class="mt-8">
        @csrf
        <div class="rounded-xl border border-white/10 bg-gray-900/50 p-6">
            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="code" class="block text-sm font-medium leading-6 text-white">Code</label>
                        <div class="mt-2">
                            <input type="text" name="code" id="code" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product code">
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-white">Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product name">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Product Details</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="serial" class="block text-sm font-medium leading-6 text-white">Serial</label>
                        <div class="mt-2">
                            <input type="text" name="serial" id="serial"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product serial">
                        </div>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium leading-6 text-white">Model</label>
                        <div class="mt-2">
                            <input type="text" name="model" id="model"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product model">
                        </div>
                    </div>

                    <div>
                        <label for="brand" class="block text-sm font-medium leading-6 text-white">Brand</label>
                        <div class="mt-2">
                            <input type="text" name="brand" id="brand"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product brand">
                        </div>
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium leading-6 text-white">Color</label>
                        <div class="mt-2">
                            <input type="text" name="color" id="color"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="Enter product color">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing and Stock -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-white mb-4">Pricing and Stock</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="price_dollars" class="block text-sm font-medium leading-6 text-white">Price (USD)</label>
                        <div class="mt-2">
                            <input type="number" name="price_dollars" id="price_dollars" step="0.01"
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium leading-6 text-white">Currency</label>
                        <div class="mt-2">
                            <select name="currency" id="currency" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="PEN">Soles (PEN)</option>
                                <option value="USD">Dollars (USD)</option>
                            </select>
                        </div>
                    </div>
                </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium leading-6 text-white">Stock</label>
                        <div class="mt-2">
                            <input type="number" name="stock" id="stock" required
                                   class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category and Description -->
            <div>
                <h2 class="text-lg font-medium text-white mb-4">Additional Information</h2>
                <div class="space-y-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium leading-6 text-white">Category</label>
                        <div class="mt-2">
                            <select name="category_id" id="category_id" required
                                    class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium leading-6 text-white">Description</label>
                        <div class="mt-2">
                            <textarea name="description" id="description" rows="4"
                                      class="block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                      placeholder="Enter product description"></textarea>
                        </div>
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
            </div>
        </div>
    </form>
</div>
@endsection

