<x-layouts::app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Item</h1>
            <p class="text-gray-600 mt-2">Update item information for {{ $flowSanitary->item_name }}</p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('flow-sanitary.update', $flowSanitary) }}" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Item Code
                        </label>
                        <input type="text" 
                               value="{{ $flowSanitary->item_code }}" 
                               readonly
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-50">
                    </div>

                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="item_name" 
                               name="item_name" 
                               value="{{ $flowSanitary->item_name }}"
                               required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('item_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">
                                Price (GH₵) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ $flowSanitary->price }}"
                                   required
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Current Quantity
                            </label>
                            <input type="number" 
                                   value="{{ $flowSanitary->quantity }}" 
                                   readonly
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-gray-50">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('flow-sanitary.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
