<x-layouts::app :title="__('Edit Item')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Edit Item</h2>
                    <p class="text-sm text-zinc-400">Update item details and pricing</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('items.update', $item) }}" class="space-y-6">
                @csrf
                @method('PUT')

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
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Category</label>
                    <select name="category" class="w-full rounded-lg border-zinc-600 bg-zinc-700 text-white focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-base" required>
                        <option value="" class="bg-zinc-700">Select Category</option>
                        <option value="Executive Wear" {{ $item->category == 'Executive Wear' ? 'selected' : '' }} class="bg-zinc-700">Executive Wear</option>
                        <option value="Native Wear" {{ $item->category == 'Native Wear' ? 'selected' : '' }} class="bg-zinc-700">Native Wear</option>
                        <option value="Ladies Wear" {{ $item->category == 'Ladies Wear' ? 'selected' : '' }} class="bg-zinc-700">Ladies Wear</option>
                        <option value="Bag Wash" {{ $item->category == 'Bag Wash' ? 'selected' : '' }} class="bg-zinc-700">Bag Wash</option>
                        <option value="Bedding and Decor" {{ $item->category == 'Bedding and Decor' ? 'selected' : '' }} class="bg-zinc-700">Bedding and Decor</option>
                        <option value="Sneakers" {{ $item->category == 'Sneakers' ? 'selected' : '' }} class="bg-zinc-700">Sneakers</option>
                        <option value="Bag" {{ $item->category == 'Bag' ? 'selected' : '' }} class="bg-zinc-700">Bag</option>
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
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Status</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="form-radio h-5 w-5 text-blue-600 bg-zinc-700 border-zinc-500 focus:ring-blue-500">
                            <span class="ml-2 text-white">Active</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="is_active" value="0" {{ !$item->is_active ? 'checked' : '' }} class="form-radio h-5 w-5 text-red-600 bg-zinc-700 border-zinc-500 focus:ring-red-500">
                            <span class="ml-2 text-white">Inactive</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        Update Item
                    </flux:button>
                    
                    <a href="{{ route('items.index') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
