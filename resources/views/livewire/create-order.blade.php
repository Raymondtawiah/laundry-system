<div>
    <form method="POST" action="{{ route('orders.store') }}" class="space-y-6">
        @csrf
        
        <!-- Customer Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Customer</label>
            <select name="customer_id" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base" required>
                <option value="" class="bg-white text-gray-900">-- Select Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer['id'] }}">
                        {{ $customer['name'] }} - {{ $customer['phone'] }}
                        @if(isset($customer['pending_balance']) && $customer['pending_balance'] > 0)
                            (Pending: GH₵{{ number_format($customer['pending_balance'], 2) }})
                        @endif
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Customers with pending payments appear first</p>
        </div>

        <!-- Delivery and Service Type -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Type</label>
                <select name="delivery_type" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base" required>
                    <option value="pickup" class="bg-white text-gray-900">Pickup (Owner comes)</option>
                    <option value="doorstep" class="bg-white text-gray-900">Doorstep Delivery</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Type</label>
                <select name="pickup_type" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base" required>
                    <option value="door_pick" class="bg-white text-gray-900">Door Pick</option>
                    <option value="self_pick" class="bg-white text-gray-900">Self Pick</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mode of Payment</label>
                <select name="mode_of_payment" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base" required>
                    <option value="Cash" class="bg-white text-gray-900">Cash</option>
                    <option value="MoMo" class="bg-white text-gray-900">MoMo</option>
                    <option value="Bank" class="bg-white text-gray-900">Bank</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                
                <!-- Selected Tags -->
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach([
                        'executive_wear' => 'Executive Wear',
                        'native_wear' => 'Native Wear',
                        'ladies_wear' => 'Ladies Wear',
                        'bedding_decor' => 'Bedding & Decor',
                        'bag_wash' => 'Bag Wash',
                        'washing' => 'Washing',
                        'ironing' => 'Ironing',
                        'casual_wear' => 'Casual Wear',
                        'deep_cleaning' => 'Deep Cleaning',
                        'sofa_cleaning' => 'Sofa Cleaning',
                    ] as $value => $label)
                        @if(in_array($value, $service_types))
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-600 text-white text-sm">
                                {{ $label }}
                                <button type="button" wire:click="removeServiceType('{{ $value }}')" class="hover:text-red-300">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </span>
                        @endif
                    @endforeach
                </div>
                
                <!-- Dropdown -->
                <div class="relative">
                    <select 
                        wire:change="addServiceType($event.target.value)" 
                        class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 py-3 px-4 text-base"
                    >
                        <option value="" class="bg-white text-gray-900">Select Service Type</option>
                        @foreach([
                            'executive_wear' => 'Executive Wear',
                            'native_wear' => 'Native Wear',
                            'ladies_wear' => 'Ladies Wear',
                            'bedding_decor' => 'Bedding & Decor',
                            'bag_wash' => 'Bag Wash',
                            'washing' => 'Washing',
                            'ironing' => 'Ironing',
                            'casual_wear' => 'Casual Wear',
                            'deep_cleaning' => 'Deep Cleaning',
                            'sofa_cleaning' => 'Sofa Cleaning',
                        ] as $value => $label)
                            <option value="{{ $value }}" class="bg-white text-gray-900" {{ in_array($value, $service_types) ? 'disabled' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Order Items</label>
                <button type="button" wire:click="addItem" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Item
                </button>
            </div>

            @if(count($orderItems) === 0)
                <div class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                    <p>No items added yet. Click "Add Item" to add items to this order.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($orderItems as $index => $orderItem)
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-1 w-full">
                                <select 
                                    wire:model="orderItems.{{ $index }}.item_id" 
                                    wire:change="onItemChange({{ $index }})"
                                    class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 text-base py-3 px-4"
                                    required
                                >
                                    <option value="" class="bg-white text-gray-900">-- Select Item --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item['id'] }}" class="bg-white text-gray-900">{{ $item['name'] }} - GH₵{{ number_format($item['price'], 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-24 text-center text-sm text-gray-700 py-2 md:py-0 font-medium">
                                GH₵{{ number_format($orderItem['unit_price'] ?? 0, 2) }}
                            </div>
                            <div class="w-full md:w-28">
                                <input 
                                    type="number" 
                                    wire:model="orderItems.{{ $index }}.quantity" 
                                    wire:change="onQuantityChange({{ $index }})"
                                    min="1" 
                                    class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 text-base text-center py-3"
                                    required
                                >
                            </div>
                            <div class="w-full md:w-32 text-right text-base font-medium text-gray-900 py-2 md:py-0">
                                GH₵{{ number_format($orderItem['subtotal'], 2) }}
                            </div>
                            <button type="button" wire:click="removeItem({{ $index }})" class="text-red-400 hover:text-red-300 p-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
            <textarea name="notes" rows="3" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900" placeholder="Any special instructions..."></textarea>
        </div>

        <!-- Total Amount -->
        <div class="flex items-center justify-between p-4 bg-blue-600/20 border border-blue-500/30 rounded-lg">
            <span class="text-lg font-medium text-gray-900">Total Amount</span>
            <span class="text-2xl font-bold text-blue-400">GH₵{{ number_format($totalAmount, 2) }}</span>
        </div>

        <!-- Hidden inputs for service type and items -->
        @if(count($service_types) > 0)
            @foreach($service_types as $serviceType)
                <input type="hidden" name="service_types[]" value="{{ $serviceType }}">
            @endforeach
        @endif
        
        @foreach($orderItems as $index => $orderItem)
            <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $orderItem['item_id'] }}">
            <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $orderItem['quantity'] }}">
            <input type="hidden" name="items[{{ $index }}][unit_price]" value="{{ $orderItem['unit_price'] }}">
        @endforeach

        <!-- Submit Buttons -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Create Order
            </button>
            
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
