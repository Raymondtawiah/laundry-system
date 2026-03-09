<x-layouts::app :title="__('Add Customer')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Add New Customer</h2>
                    <p class="text-sm text-zinc-400">Add a new customer to your laundry</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
                @csrf

                <div>
                    <flux:input 
                        name="name" 
                        label="Customer Name" 
                        type="text" 
                        required 
                        autofocus 
                        placeholder="Enter customer name"
                    />
                </div>

                <div>
                    <flux:input 
                        name="email" 
                        label="Email Address" 
                        type="email" 
                        placeholder="Enter email address (optional)"
                    />
                </div>

                <div>
                    <flux:input 
                        name="phone" 
                        label="Phone Number" 
                        type="tel" 
                        required 
                        placeholder="Enter phone number"
                    />
                </div>

                @if(auth()->user()->role === 'admin')
                <div>
                    <label for="branch" class="block text-sm font-medium text-zinc-300 mb-2">Branch</label>
                    <select 
                        name="branch" 
                        id="branch" 
                        class="w-full rounded-lg border-zinc-600 bg-zinc-700 text-white focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-base"
                    >
                        <option value="">Select Branch</option>
                        <option value="Daasebre">Daasebre</option>
                        <option value="Nyamekrom">Nyamekrom</option>
                        <option value="KTU">KTU</option>
                    </select>
                </div>
                @endif

                <div>
                    <flux:textarea 
                        name="address" 
                        label="Address" 
                        placeholder="Enter customer address (optional)"
                        rows="2"
                    />
                </div>

                <div>
                    <flux:textarea 
                        name="notes" 
                        label="Notes" 
                        placeholder="Additional notes about this customer (optional)"
                        rows="2"
                    />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        Add Customer
                    </flux:button>
                    
                    <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
