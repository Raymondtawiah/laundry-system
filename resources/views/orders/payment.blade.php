<x-layouts::app :title="__('Record Payment')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Record Payment</h2>
                    <p class="text-sm text-zinc-400">Order #ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mb-6 rounded-lg bg-zinc-700/50 p-4 border border-zinc-600">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-zinc-400">Customer</p>
                        <p class="font-medium text-white">{{ $order->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-400">Total Amount</p>
                        <p class="font-medium text-white">GH₵{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-400">Amount Paid</p>
                        <p class="font-medium text-green-400">GH₵{{ number_format($order->amount_paid, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-zinc-400">Balance Due</p>
                        <p class="font-bold text-red-400">GH₵{{ number_format($order->balance, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form method="POST" action="{{ route('orders.recordPayment', $order) }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Payment Amount (GH₵)</label>
                    <input 
                        type="number" 
                        name="amount" 
                        step="0.01" 
                        min="0.01" 
                        max="{{ $order->balance }}"
                        class="w-full rounded-lg border-zinc-600 bg-zinc-700 text-white focus:border-green-500 focus:ring-green-500"
                        placeholder="Enter payment amount"
                        required
                    >
                    <p class="mt-1 text-xs text-zinc-400">Maximum: GH₵{{ number_format($order->balance, 2) }}</p>
                </div>

                <!-- Quick Amount Buttons -->
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Quick Amounts</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setAmount({{ $order->balance }})" class="px-3 py-1 text-sm rounded-md bg-zinc-600 text-white hover:bg-zinc-500">
                            Full Balance
                        </button>
                        <button type="button" onclick="setAmount({{ $order->balance / 2 }})" class="px-3 py-1 text-sm rounded-md bg-zinc-600 text-white hover:bg-zinc-500">
                            50%
                        </button>
                        <button type="button" onclick="setAmount({{ min(10, $order->balance) }})" class="px-3 py-1 text-sm rounded-md bg-zinc-600 text-white hover:bg-zinc-500">
                            GH₵10
                        </button>
                        <button type="button" onclick="setAmount({{ min(20, $order->balance) }})" class="px-3 py-1 text-sm rounded-md bg-zinc-600 text-white hover:bg-zinc-500">
                            GH₵20
                        </button>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Record Payment
                    </button>
                    
                    <a href="{{ route('orders.index') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setAmount(amount) {
            document.querySelector('input[name="amount"]').value = amount.toFixed(2);
        }
    </script>
</x-layouts::app>
