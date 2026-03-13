<x-layouts::app :title="__('Items')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Items</h1>
                <p class="text-sm text-gray-500">Manage laundry items and pricing</p>
            </div>
            <a href="{{ route('items.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Item
            </a>
        </div>

        <!-- Search -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('items.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search items..." 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-40">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="" class="bg-white text-gray-900">All Categories</option>
                        @forelse($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }} class="bg-white text-gray-900">{{ $category }}</option>
                        @empty
                            <option value="" disabled class="bg-white text-gray-900">No categories available</option>
                        @endforelse
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('category'))
                    <a href="{{ route('items.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Items Grid -->
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($items as $item)
                <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $item->category }}</p>
                        </div>
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('items.edit', $item->id) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <div>
                            @if($item->price > 0)
                                <span class="text-lg font-bold text-blue-600">GH₵{{ number_format($item->price, 2) }}</span>
                            @else
                                <span class="text-lg font-bold text-green-600">Free</span>
                            @endif
                            @if($item->min_weight)
                                <span class="text-xs text-gray-500">/ {{ $item->min_weight }}kg min</span>
                            @endif
                        </div>
                        @if($item->is_active)
                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                        @endif
                    </div>
                    @if($item->description)
                        <p class="mt-2 text-xs text-gray-500 line-clamp-2">{{ $item->description }}</p>
                    @endif
                </div>
            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="rounded-full bg-gray-100 p-4">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No items found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding your first item.</p>
                        <a href="{{ route('items.create') }}" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Add Item
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($items->hasPages())
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
