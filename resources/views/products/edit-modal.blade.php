<div class="p-6">
    <h3 class="text-lg font-medium leading-6 text-white mb-4">Edit Product</h3>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ $product->name }}" 
                       class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required>
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-300">Price</label>
                <input type="number" name="price" id="price" value="{{ $product->price }}" step="0.01"
                       class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required>
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-gray-300">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ $product->stock }}"
                       class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $product->description }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="closeEditModal()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-transparent hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Product
            </button>
        </div>
    </form>
</div>

