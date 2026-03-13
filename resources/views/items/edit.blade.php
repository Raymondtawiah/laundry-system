<x-layouts::app :title="__('Edit Item')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Item</h1>
                <p class="text-sm text-gray-500">Update item details and pricing</p>
            </div>
            <a href="{{ route('items.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Items
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Edit Item</h2>
                            <p class="text-sm text-gray-500">Update item details</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('items.update', $item) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:input 
                                    name="name" 
                                    label="Item Name" 
                                    type="text" 
                                    required 
                                    autofocus 
                                    placeholder="e.g., T-Shirt, Pants, Dress"
                                    :value="$item->name"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base" required>
                                    <option value="" class="bg-white text-gray-900">Select Category</option>
                                    <option value="Executive Wear" {{ $item->category == 'Executive Wear' ? 'selected' : '' }} class="bg-white text-gray-900">🧥 Executive Wear</option>
                                    <option value="Native Wear" {{ $item->category == 'Native Wear' ? 'selected' : '' }} class="bg-white text-gray-900">👘 Native Wear</option>
                                    <option value="Ladies Wear" {{ $item->category == 'Ladies Wear' ? 'selected' : '' }} class="bg-white text-gray-900">👗 Ladies Wear</option>
                                    <option value="Bag Wash" {{ $item->category == 'Bag Wash' ? 'selected' : '' }} class="bg-white text-gray-900">👜 Bag Wash</option>
                                    <option value="Bedding & Decor" {{ $item->category == 'Bedding & Decor' ? 'selected' : '' }} class="bg-white text-gray-900">🛏️ Bedding & Decor</option>
                                    <option value="Washing" {{ $item->category == 'Washing' ? 'selected' : '' }} class="bg-white text-gray-900">🧼 Washing</option>
                                    <option value="Ironing" {{ $item->category == 'Ironing' ? 'selected' : '' }} class="bg-white text-gray-900">✨ Ironing</option>
                                    <option value="Casual Wear" {{ $item->category == 'Casual Wear' ? 'selected' : '' }} class="bg-white text-gray-900">👕 Casual Wear</option>
                                    <option value="Deep Cleaning" {{ $item->category == 'Deep Cleaning' ? 'selected' : '' }} class="bg-white text-gray-900">✨ Deep Cleaning</option>
                                    <option value="Sofa Cleaning" {{ $item->category == 'Sofa Cleaning' ? 'selected' : '' }} class="bg-white text-gray-900">🛋️ Sofa Cleaning</option>
                                    <option value="Sneakers" {{ $item->category == 'Sneakers' ? 'selected' : '' }} class="bg-white text-gray-900">👟 Sneakers</option>
                                    <option value="Bag" {{ $item->category == 'Bag' ? 'selected' : '' }} class="bg-white text-gray-900">🎒 Bag</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <flux:input 
                                name="price" 
                                label="Price (GH₵)" 
                                type="number" 
                                step="0.01"
                                min="0"
                                required 
                                placeholder="0.00"
                                :value="$item->price"
                            />
                        </div>

                        <div>
                            <flux:textarea 
                                name="description" 
                                label="Description" 
                                placeholder="Optional description for this item"
                                rows="3"
                            >{{ $item->description }}</flux:textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Status</label>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="form-radio h-5 w-5 text-green-600 border-gray-300 focus:ring-green-500">
                                    <span class="text-sm font-medium text-gray-700">Active</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="0" {{ !$item->is_active ? 'checked' : '' }} class="form-radio h-5 w-5 text-red-600 border-gray-300 focus:ring-red-500">
                                    <span class="text-sm font-medium text-gray-700">Inactive</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                            <flux:button type="submit" variant="primary">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Item
                            </flux:button>
                            
                            <a href="{{ route('items.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Item Info -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Item Info</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Created</span>
                            <span class="text-sm font-medium text-gray-900">{{ $item->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $item->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Status</span>
                            @if($item->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Available Categories</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Executive Wear</span>
                        <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Native Wear</span>
                        <span class="px-2 py-1 text-xs font-medium bg-pink-100 text-pink-700 rounded-full">Ladies Wear</span>
                        <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Bag Wash</span>
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Bedding & Decor</span>
                        <span class="px-2 py-1 text-xs font-medium bg-cyan-100 text-cyan-700 rounded-full">Washing</span>
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Ironing</span>
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">Casual Wear</span>
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Deep Cleaning</span>
                        <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Sofa Cleaning</span>
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Sneakers</span>
                        <span class="px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-full">Bag</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
