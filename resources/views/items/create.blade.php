<x-layouts::app :title="__('Add Item')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Add New Item</h2>
                    <p class="text-sm text-zinc-400">Add a new laundry item with pricing</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('items.store') }}" class="space-y-6">
                @csrf

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
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Category</label>
                    <select name="category" class="w-full rounded-lg border-zinc-600 bg-zinc-700 text-white focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-base" required>
                        <option value="" class="bg-zinc-700">Select Category</option>
                        <option value="Executive Wear" class="bg-zinc-700">Executive Wear</option>
                        <option value="Native Wear" class="bg-zinc-700">Native Wear</option>
                        <option value="Ladies Wear" class="bg-zinc-700">Ladies Wear</option>
                        <option value="Bag Wash" class="bg-zinc-700">Bag Wash</option>
                        <option value="Bedding and Decor" class="bg-zinc-700">Bedding and Decor</option>
                        <option value="Sneakers" class="bg-zinc-700">Sneakers</option>
                        <option value="Bag" class="bg-zinc-700">Bag</option>
                    </select>
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

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        Add Item
                    </flux:button>
                    
                    <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
