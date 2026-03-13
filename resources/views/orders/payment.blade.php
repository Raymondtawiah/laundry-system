<x-layouts::app :title="__('Record Payment')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Record Payment</h1>
                <p class="text-sm text-gray-500">Order #ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Order
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Form -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Make Payment</h2>
                        <p class="text-sm text-gray-500">Enter payment details below</p>
                    </div>
                </div>

                <!-- Payment Form -->
                <form method="POST" action="{{ route('orders.recordPayment', $order) }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Amount (GH₵)</label>
                        <input 
                            type="number" 
                            name="amount" 
                            step="0.01" 
                            min="0.01" 
                            max="{{ $order->balance }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                            placeholder="0.00"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-500">Maximum: GH₵{{ number_format($order->balance, 2) }}</p>
                    </div>

                    <!-- Quick Amount Buttons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quick Amounts</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setAmount({{ $order->balance }})" class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 font-medium transition-colors">
                                Full Balance
                            </button>
                            <button type="button" onclick="setAmount({{ $order->balance / 2 }})" class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 font-medium transition-colors">
                                50%
                            </button>
                            <button type="button" onclick="setAmount({{ min(10, $order->balance) }})" class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 font-medium transition-colors">
                                GH₵10
                            </button>
                            <button type="button" onclick="setAmount({{ min(20, $order->balance) }})" class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 font-medium transition-colors">
                                GH₵20
                            </button>
                            <button type="button" onclick="setAmount({{ min(50, $order->balance) }})" class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 font-medium transition-colors">
                                GH₵50
                            </button>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Record Payment
                        </button>
                        
                        <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Customer Info -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Customer</h3>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-lg font-bold text-gray-600">
                            {{ strtoupper(substr($order->customer->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->customer->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Payment Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Total Amount</span>
                            <span class="text-lg font-bold text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <span class="text-sm text-gray-600">Amount Paid</span>
                            <span class="text-lg font-bold text-green-600">GH₵{{ number_format($order->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                            <span class="text-sm text-gray-600">Balance Due</span>
                            <span class="text-lg font-bold text-red-600">GH₵{{ number_format($order->balance, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Payment Status</h3>
                    <div class="flex items-center gap-3">
                        @switch($order->payment_status)
                            @case('paid')
                                <span class="rounded-full bg-green-100 p-2">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-green-700">Fully Paid</p>
                                    <p class="text-sm text-gray-500">No balance remaining</p>
                                </div>
                                @break
                            @case('partial')
                                <span class="rounded-full bg-yellow-100 p-2">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-yellow-700">Partial Payment</p>
                                    <p class="text-sm text-gray-500">Balance remaining</p>
                                </div>
                                @break
                            @default
                                <span class="rounded-full bg-red-100 p-2">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-red-700">Unpaid</p>
                                    <p class="text-sm text-gray-500">Awaiting payment</p>
                                </div>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setAmount(amount) {
            document.querySelector('input[name="amount"]').value = amount.toFixed(2);
        }
    </script>
</x-layouts::app>
