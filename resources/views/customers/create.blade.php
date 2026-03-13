<x-layouts::app :title="__('Add Customer')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Add Customer</h1>
                <p class="text-sm text-gray-500">Add a new customer to your laundry</p>
            </div>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">New Customer</h2>
                            <p class="text-sm text-gray-500">Fill in the customer details</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                    name="phone" 
                                    label="Phone Number" 
                                    type="tel" 
                                    required 
                                    placeholder="Enter phone number"
                                />
                            </div>
                        </div>

                        <div>
                            <flux:input 
                                name="email" 
                                label="Email Address" 
                                type="email" 
                                placeholder="Enter email address (optional)"
                            />
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">Branch</label>
                            <select 
                                name="branch" 
                                id="branch" 
                                class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 py-3 px-4 text-base"
                            >
                                <option value="" class="bg-white text-gray-900">Select Branch</option>
                                <option value="Daasebre" class="bg-white text-gray-900">Daasebre</option>
                                <option value="Nyamekrom" class="bg-white text-gray-900">Nyamekrom</option>
                                <option value="KTU" class="bg-white text-gray-900">KTU</option>
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

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                            <flux:button type="submit" variant="primary">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Add Customer
                            </flux:button>
                            
                            <a href="{{ route('customers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
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
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Phone number is required for order notifications</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Customer can view their order status online</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Branch assignment helps organize customers</span>
                        </li>
                    </ul>
                </div>

                <!-- Required Fields -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Required Fields</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Required</span>
                            <span class="text-sm text-gray-600">Customer Name</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Required</span>
                            <span class="text-sm text-gray-600">Phone Number</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Optional</span>
                            <span class="text-sm text-gray-600">Email</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">Optional</span>
                            <span class="text-sm text-gray-600">Address</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
