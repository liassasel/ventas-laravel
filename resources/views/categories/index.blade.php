@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-white">Categories</h1>
            <p class="mt-2 text-sm text-gray-400">A list of all the categories in your electronics store.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('categories.create') }}" 
               class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-black 
                      transition-all hover:bg-gray-200 focus-visible:outline focus-visible:outline-2 
                      focus-visible:outline-offset-2 focus-visible:outline-white">
                Add Category
            </a>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-0">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Parent Category</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($categories as $category)
                            <tr class="hover:bg-white/5">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-0">{{ $category->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $category->parent ? $category->parent->name : 'None' }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-[#0070f3] hover:text-[#0761d1]">Edit</a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block ml-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this category?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @if($category->children)
                                @foreach($category->children as $child)
                                    <tr class="hover:bg-white/5">
                                        <td class="whitespace-nowrap py-4  pr-3 text-sm font-medium text-white sm:pl-0 pl-8">-- {{ $child->name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">{{ $category->name }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                            <a href="{{ route('categories.edit', $child->id) }}" class="text-[#0070f3] hover:text-[#0761d1]">Edit</a>
                                            <form action="{{ route('categories.destroy', $child->id) }}" method="POST" class="inline-block ml-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this category?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

