<x-layouts::app :title="__('Customer Details')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h1>
                <p class="text-sm text-gray-500">Customer Details & Order History</p>
            </div>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Customer Info -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <!-- Profile Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-xl font-bold text-gray-600">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h2>
                            @if($customer->branch)
                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700">
                                    {{ $customer->branch }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="rounded-lg bg-gray-100 p-2">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Phone</p>
                                <p class="text-sm font-medium text-gray-900">{{ $customer->phone }}</p>
                            </div>
                        </div>
                        
                        @if($customer->email)
                        <div class="flex items-start gap-3">
                            <div class="rounded-lg bg-gray-100 p-2">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $customer->email }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($customer->address)
                        <div class="flex items-start gap-3">
                            <div class="rounded-lg bg-gray-100 p-2">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Address</p>
                                <p class="text-sm font-medium text-gray-900">{{ $customer->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($customer->notes)
                        <div class="flex items-start gap-3">
                            <div class="rounded-lg bg-gray-100 p-2">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Notes</p>
                                <p class="text-sm text-gray-700">{{ $customer->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="rounded-lg bg-blue-100 p-2">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Total Orders</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $customer->orders->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="rounded-lg bg-green-100 p-2">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Total Spent</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">GH₵{{ number_format($customer->orders->sum('total_amount'), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="rounded-lg bg-red-100 p-2">
                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Pending Balance</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">GH₵{{ number_format($customer->orders->sum('balance'), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 bg-gray-50 px-4 sm:px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">Order History</h2>
                        <p class="text-sm text-gray-500">All orders for this customer</p>
                    </div>
                    
                    @if($customer->orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Payment</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($customer->orders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                                                    @if($order->pickup_type === 'doorstep')
                                                        <span class="inline-flex rounded-full bg-purple-100 px-1.5 py-0.5 text-xs font-medium text-purple-700" title="Home Delivery">
                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                            </svg>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-4 py-3">
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Pending</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">In Progress</span>
                                                        @break
                                                    @case('ready')
                                                        <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">Ready</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Delivered</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Cancelled</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($order->payment_status === 'paid')
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Paid
                                                    </span>
                                                @elseif($order->payment_status === 'partial')
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-yellow-700">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Partial
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-medium text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</div>
                                                @if($order->balance > 0)
                                                    <div class="text-xs text-red-600">Balance: GH₵{{ number_format($order->balance, 2) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors" title="View Details">
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
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="rounded-full bg-gray-100 p-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-sm font-medium text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-sm text-gray-500">This customer hasn't placed any orders.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
