<x-layouts::app :title="__('Add Item')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Add Item</h1>
                <p class="text-sm text-gray-500">Add a new laundry item with pricing</p>
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
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">New Item</h2>
                            <p class="text-sm text-gray-500">Enter item details and pricing</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('items.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:input 
                                    name="name" 
                                    label="Item Name" 
                                    type="text" 
                                    required 
                                    autofocus 
                                    placeholder="e.g., T-Shirt, Pants, Dress"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 py-3 px-4 text-base" required>
                                    <option value="" class="bg-white text-gray-900">Select Category</option>
                                    <option value="Executive Wear" class="bg-white text-gray-900">🧥 Executive Wear</option>
                                    <option value="Native Wear" class="bg-white text-gray-900">👘 Native Wear</option>
                                    <option value="Ladies Wear" class="bg-white text-gray-900">👗 Ladies Wear</option>
                                    <option value="Bag Wash" class="bg-white text-gray-900">👜 Bag Wash</option>
                                    <option value="Bedding & Decor" class="bg-white text-gray-900">🛏️ Bedding & Decor</option>
                                    <option value="Washing" class="bg-white text-gray-900">🧼 Washing</option>
                                    <option value="Ironing" class="bg-white text-gray-900">✨ Ironing</option>
                                    <option value="Casual Wear" class="bg-white text-gray-900">👕 Casual Wear</option>
                                    <option value="Deep Cleaning" class="bg-white text-gray-900">✨ Deep Cleaning</option>
                                    <option value="Sofa Cleaning" class="bg-white text-gray-900">🛋️ Sofa Cleaning</option>
                                    <option value="Sneakers" class="bg-white text-gray-900">👟 Sneakers</option>
                                    <option value="Bag" class="bg-white text-gray-900">🎒 Bag</option>
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
                            />
                        </div>

                        <div>
                            <flux:textarea 
                                name="description" 
                                label="Description" 
                                placeholder="Optional description for this item"
                                rows="3"
                            />
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                            <flux:button type="submit" variant="primary">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Add Item
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
                <!-- Quick Tips -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Quick Tips</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Match categories to your service types</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Set competitive prices for each item</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Categories match order service types</span>
                        </li>
                    </ul>
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
