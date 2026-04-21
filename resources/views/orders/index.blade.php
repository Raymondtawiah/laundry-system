<x-layouts::app :title="__('Orders')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Orders</h1>
                <p class="text-sm text-gray-500">Manage and track all laundry orders</p>
            </div>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all shadow-lg shadow-blue-500/25">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Order
            </a>
        </div>

        <!-- Filters -->
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm hover:shadow-md transition-shadow">
            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by order ID or customer name..." 
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="w-full sm:w-40">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status" id="status" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <label for="payment" class="block text-sm font-medium text-gray-700 mb-1.5">Payment</label>
                    <select name="payment" id="payment" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <option value="">All Payment</option>
                        <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ request('payment') === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="unpaid" {{ request('payment') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1.5">Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" 
                        class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'date', 'payment']))
                    <a href="{{ route('orders.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            @if($orders->isEmpty())
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-gray-100 p-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-medium text-gray-900">No orders found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first order.</p>
                    <a href="{{ route('orders.create') }}" class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Create Order
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 hidden md:table-cell">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 hidden sm:table-cell">Pickup</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 hidden lg:table-cell">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="text-sm font-semibold text-gray-900">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-medium text-xs">
                                                {{ substr($order->customer->name, 0, 2) }}
                                            </div>
                                            <div class="text-sm">
                                                <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                                                <p class="text-gray-500 text-xs">{{ $order->customer->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        @php
                                            $serviceTypes = is_array($order->service_type) ? $order->service_type : json_decode($order->service_type, true);
                                            if (is_string($serviceTypes)) {
                                                $serviceTypes = [$serviceTypes];
                                            }
                                        @endphp
                                        @if($serviceTypes && count($serviceTypes) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($serviceTypes as $serviceType)
                                                    @switch($serviceType)
                                                        @case('washing')
                                                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Exec. Wear</span>
                                                            @break
                                                        @case('ironing')
                                                            <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">Native Wear</span>
                                                            @break
                                                        @case('drying')
                                                            <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full">Ladies Wear</span>
                                                            @break
                                                        @case('bag wash')
                                                            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Bag Wash</span>
                                                            @break
                                                        @case('bedding_decor')
                                                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Bedding</span>
                                                            @break
                                                        @case('sneakers')
                                                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded-full">Sneakers</span>
                                                            @break
                                                        @case('bag')
                                                            <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Bag</span>
                                                            @break
                                                        @case('ironing')
                                                            <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full">Ironing</span>
                                                            @break
                                                        @case('deep_cleaning')
                                                            <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full">Deep Cleaning</span>
                                                            @break
                                                        @default
                                                            <span class="text-xs font-medium text-gray-600 bg-gray-50 px-2 py-0.5 rounded-full">{{ $serviceType }}</span>
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">Pending</span>
                                                @break
                                            @case('in_progress')
                                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">In Progress</span>
                                                @break
                                            @case('ready')
                                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">Ready</span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Delivered</span>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell">
                                        <span class="text-sm text-gray-600">
                                            @if($order->pickup_type === 'pickup')
                                                Pickup
                                            @elseif($order->pickup_type === 'dropoff')
                                                Drop Off
                                            @else
                                                {{ ucfirst($order->pickup_type ?? '-') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell">
                                        @if($order->payment_status === 'paid')
                                            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Paid</span>
                                        @elseif($order->payment_status === 'partial')
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">Partial</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-semibold text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-500">{{ $order->created_at->format('M d, H:i') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('orders.show', $order->id) }}" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors" title="View">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @if(auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('orders.destroy', $order->id) }}" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="border-t border-gray-100 px-4 py-3 bg-gray-50">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts::app>
