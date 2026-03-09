<x-layouts::app :title="__('Dashboard')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laundry Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back! Here's what's happening with your laundry today.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid auto-rows_min gap-4 md:grid-cols-4">
            <!-- Total Orders -->
            <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-500">Needs attention</span>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivered Today</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $completedToday }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">GH₵{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Recent Orders -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700 md:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">View all</a>
                </div>
                
                @if($recentOrders->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first order.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Order ID</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Customer</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Service</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="py-3 text-sm font-medium text-gray-900 dark:text-white">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->customer->name }}</td>
                                        <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
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
                                                                <span class="text-xs font-medium text-blue-800 dark:text-blue-200 truncate max-w-[80px]">Exec. Wear</span>
                                                                @break
                                                            @case('ironing')
                                                                <span class="text-xs font-medium text-purple-800 dark:text-purple-200 truncate max-w-[80px]">Native Wear</span>
                                                                @break
                                                            @case('drying')
                                                                <span class="text-xs font-medium text-pink-800 dark:text-pink-200 truncate max-w-[80px]">Ladies Wear</span>
                                                                @break
                                                            @case('bag wash')
                                                                <span class="text-xs font-medium text-amber-800 dark:text-amber-200 truncate max-w-[80px]">Bag Wash</span>
                                                                @break
                                                            @case('bedding_decor')
                                                                <span class="text-xs font-medium text-green-800 dark:text-green-200 truncate max-w-[80px]">Bedding</span>
                                                                @break
                                                            @case('sneakers')
                                                                <span class="text-xs font-medium text-red-800 dark:text-red-200 truncate max-w-[80px]">Sneakers</span>
                                                                @break
                                                            @case('bag')
                                                                <span class="text-xs font-medium text-orange-800 dark:text-orange-200 truncate max-w-[80px]">Bag</span>
                                                                @break
                                                            @case('deep_cleaning')
                                                                <span class="text-xs font-medium text-cyan-800 dark:text-cyan-200 truncate max-w-[80px]">Ironing</span>
                                                                @break
                                                            @default
                                                                <span class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate max-w-[80px]">{{ $serviceType }}</span>
                                                        @endswitch
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-200">Pending</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">In Progress</span>
                                                    @break
                                                @case('ready')
                                                    <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">Ready</span>
                                                    @break
                                                @case('completed')
                                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Delivered</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">Cancelled</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="py-3 text-sm text-gray-500 dark:text-gray-400">GH₵{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                    
                                                <!-- Send WhatsApp Button (only for Ready orders) -->
                                                @if($order->status === 'ready')
                                                    <a href="{{ route('orders.receipt.whatsapp', $order->id) }}" target="_blank" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Send Receipt via WhatsApp">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('orders.create') }}" class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Order
                    </a>
                    <a href="{{ route('customers.create') }}" class="flex w-full items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Add Customer
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex w-full items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        All Orders
                    </a>
                    <a href="{{ route('items.create') }}" class="flex w-full items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Add Item
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
