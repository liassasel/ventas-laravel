@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold leading-tight">Create New Category</h1>
            <a href="{{ route('categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <span>Back to Categories</span>
            </a>
        </div>
        
        <div class="mt-8">
            <form action="{{ route('categories.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Category Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" required placeholder="Enter category name">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_id">
                        Parent Category (Optional)
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="parent_id" name="parent_id">
                        <option value="">None</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

