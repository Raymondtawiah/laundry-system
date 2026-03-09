<x-layouts::app :title="__('Order Details')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order Details</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">View order information and details</p>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Order ID</span>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <p>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                        @break
                                    @case('in_progress')
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">In Progress</span>
                                        @break
                                    @case('ready')
                                        <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900 dark:text-purple-200">Ready</span>
                                        @break
                                    @case('completed')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">Delivered</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Branch</span>
                            <p class="text-gray-900 dark:text-white">{{ $order->branch ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Created Date</span>
                            <p class="text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Delivery & Service</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Delivery Type</span>
                            <p class="text-gray-900 dark:text-white">
                                @if($order->delivery_type === 'pickup')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        Pickup (Owner comes)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        Doorstep Delivery
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Pickup Type</span>
                            <p class="text-gray-900 dark:text-white">
                                @if($order->pickup_type === 'door_pick')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                        Door Pick
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-800 dark:bg-teal-900 dark:text-teal-200">
                                        Self Pick
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Service Type</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @php
                                    $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                                    if (is_string($serviceTypes)) {
                                        $serviceTypes = [$serviceTypes];
                                    }
                                @endphp
                                @if($serviceTypes && count($serviceTypes) > 0)
                                    @foreach($serviceTypes as $serviceType)
                                        @switch($serviceType)
                                            @case('washing')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">🧥 Executive Wear</span>
                                                @break
                                            @case('ironing')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900 dark:text-purple-200">👘 Native Wear</span>
                                                @break
                                            @case('drying')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-pink-100 px-3 py-1 text-xs font-semibold text-pink-800 dark:bg-pink-900 dark:text-pink-200">👗 Ladies Wear</span>
                                                @break
                                            @case('bag wash')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900 dark:text-amber-200">👜 Bag Wash</span>
                                                @break
                                            @case('bedding_decor')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">🛏️ Bedding and Decor</span>
                                                @break
                                            @case('sneakers')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">👟 Sneakers</span>
                                                @break
                                            @case('bag')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800 dark:bg-orange-900 dark:text-orange-200">🎒 Bag</span>
                                                @break
                                            @case('deep_cleaning')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">✨ Ironing</span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-200">{{ $serviceType }}</span>
                                        @endswitch
                                    @endforeach
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Created By</span>
                            <p class="text-gray-900 dark:text-white">{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($order->notes)
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h2>
                    <p class="text-gray-700 dark:text-gray-300">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h2>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Name</span>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $order->customer->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
                            <p class="text-gray-900 dark:text-white">{{ $order->customer->phone ?? 'N/A' }}</p>
                        </div>
                        @if($order->customer->address)
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Address</span>
                            <p class="text-gray-900 dark:text-white">{{ $order->customer->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Total Amount</span>
                            <span class="text-gray-900 dark:text-white font-bold">GH₵{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Amount Paid</span>
                            <span class="text-green-600 dark:text-green-400 font-medium">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Balance</span>
                            <span class="text-red-600 dark:text-red-400 font-medium">GH₵{{ number_format($order->balance, 2) }}</span>
                        </div>
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Payment Status</span>
                            <p>
                                @switch($order->payment_status)
                                    @case('unpaid')
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">Unpaid</span>
                                        @break
                                    @case('partial')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Partial</span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">Paid</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-3">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center w-full gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
