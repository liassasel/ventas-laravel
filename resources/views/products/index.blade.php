@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Products</h1>
            <p class="mt-2 text-sm text-gray-400">A list of all products in your inventory system.</p>
        </div>
    </div>

    <div class="mt-8 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
        <div class="flex-1">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                @csrf
                <input type="file" name="file" required 
                       class="text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 
                              file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                <button type="submit" 
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold 
                               text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 
                               focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Import Products
                </button>
            </form>
        </div>
        <a href="{{ route('products.create') }}" 
           class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black 
                  transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 
                  focus-visible:outline-offset-2 focus-visible:outline-white">
            Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="mt-4 rounded-md bg-green-500/10 border border-green-500/50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 rounded-md bg-red-500/10 border border-red-500/50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="mt-8 bg-gray-900/50 p-4 rounded-lg border border-white/10">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label for="store_id" class="block text-sm font-medium text-white">Store</label>
                <select name="store_id" id="store_id" class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
                    <option value="">All Stores</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-white">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                       class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-white">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                       class="mt-1 block w-full rounded-md border-0 bg-white/5 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6">
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Code</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Category</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Store</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Stock</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Price (PEN)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Price (USD)</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($products as $product)
                            <tr class="hover:bg-white/5">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">
                                    {{ $product->code }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $product->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ optional($product->category)->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    {{ optional($product->mainStore)->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $product->stock }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    S/. {{ number_format($product->price_soles, 2) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                    $ {{ number_format($product->price_dollars, 2) }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-400 hover:text-indigo-300">
                                        Edit<span class="sr-only">, {{ $product->name }}</span>
                                    </a>
                                    @if(auth()->user()->is_admin)
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this product?')">
                                            Delete
                                        </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-4 text-sm text-gray-300 text-center">
                                    No products found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

