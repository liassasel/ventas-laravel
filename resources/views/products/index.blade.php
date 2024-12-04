@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div>
    <div class="mb-4 flex justify-between">
        <h2 class="text-xl font-bold text-white">Products List</h2>
        <a href="{{ route('products.create') }}" class="rounded bg-white px-4 py-2 font-bold text-black hover:bg-gray-200">
            Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700 bg-black">
                @foreach($products as $product)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-300">{{ $product->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-300">${{ number_format($product->price, 2) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-300">{{ $product->stock }}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">{{ Str::limit($product->description, 50) }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-300">
                            <a href="{{ route('products.edit', $product->id) }}" class="mr-2 text-blue-500 hover:text-blue-700">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

