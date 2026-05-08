<x-layouts::app>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Add New Item</h1>
            <p class="text-gray-600 text-sm mt-1">Add a new sanitary ware item to your inventory</p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('flow-sanitary.store') }}" class="p-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="item_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Item Code <span class="text-red-500">*</span>
                        </label>
                        <select id="item_code" 
                                name="item_code" 
                                required
                                class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select item code</option>
                            @for($i = 1; $i <= 44; $i++)
                                <option value="F-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}">
                                    F-{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                        @error('item_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="item_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="item_name" 
                               name="item_name" 
                               required
                               class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., Toilet Seat">
                        @error('item_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Price (GH₵) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="price" 
                               name="price" 
                               step="0.01" 
                               min="0" 
                               required
                               class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        @error('price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Initial Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               min="0" 
                               required
                               class="block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0">
                        @error('quantity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('flow-sanitary.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
