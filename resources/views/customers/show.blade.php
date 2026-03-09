<x-layouts::app :title="__('Customer Details')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customer Details</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">View customer information and order history</p>
            </div>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Name</span>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
                            <p class="text-gray-900 dark:text-white">{{ $customer->phone }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                            <p class="text-gray-900 dark:text-white">{{ $customer->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Branch</span>
                            <p>
                                @if($customer->branch)
                                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                        {{ $customer->branch }}
                                    </span>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </p>
                        </div>
                        @if($customer->address)
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Address</span>
                            <p class="text-gray-900 dark:text-white">{{ $customer->address }}</p>
                        </div>
                        @endif
                        @if($customer->notes)
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Notes</span>
                            <p class="text-gray-700 dark:text-gray-300">{{ $customer->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Total Orders</span>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->orders->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Total Spent</span>
                            <span class="text-xl font-bold text-green-600 dark:text-green-400">GH₵{{ number_format($customer->orders->sum('total_amount'), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Pending Balance</span>
                            <span class="text-xl font-bold text-red-600 dark:text-red-400">GH₵{{ number_format($customer->orders->sum('balance'), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order History</h2>
                    @if($customer->orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Order ID</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Total</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Balance</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($customer->orders as $order)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-900 dark:text-white">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">In Progress</span>
                                                        @break
                                                    @case('ready')
                                                        <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-semibold text-purple-800 dark:bg-purple-900 dark:text-purple-200">Ready</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">Delivered</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">Cancelled</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">GH₵{{ number_format($order->total_amount, 2) }}</td>
                                            <td class="px-4 py-3">
                                                @if($order->balance > 0)
                                                    <span class="text-red-600 dark:text-red-400">GH₵{{ number_format($order->balance, 2) }}</span>
                                                @else
                                                    <span class="text-green-600 dark:text-green-400">Paid</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No orders found for this customer.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
