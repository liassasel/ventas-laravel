@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-white">Products</h1>
        <p class="mt-2 text-sm text-gray-400">A list of all the products in your electronics store.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0">
        <a href="{{ route('products.create') }}" 
           class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
            Add Product
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
            <div class="overflow-hidden rounded-xl border border-white/10 bg-gray-900/50">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-900">
                        <tr>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Code</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Category</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Price (Soles)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Price (Dollars)</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Stock</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($products as $product)
                            <tr class="hover:bg-white/5">
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">{{ $product->code ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-white">{{ $product->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $product->category->name ?? 'Uncategorized' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">S/. {{ number_format($product->price_soles ?? 0, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">$ {{ number_format($product->price_dollars ?? 0, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">{{ $product->stock }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-[#0070f3] hover:text-[#0761d1]">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block ml-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this product?')">
                                            Delete
                                        </button>
                                    </form>
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

