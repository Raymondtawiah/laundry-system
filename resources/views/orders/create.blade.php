<x-layouts::app :title="__('Create Order')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Create New Order</h2>
                    <p class="text-sm text-zinc-400">Add items and select customer</p>
                </div>
            </div>
            
            <livewire:create-order />
        </div>
    </div>
</x-layouts::app>
