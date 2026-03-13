<x-layouts::app :title="__('Dashboard')">
    <x-flash-message />
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Welcome back!</h1>
            <p class="text-sm text-gray-500">Here's what's happening with your laundry business today.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Total Orders -->
            <div class="relative overflow-hidden rounded-xl bg-white p-4 sm:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="relative overflow-hidden rounded-xl bg-white p-4 sm:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Pending</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 sm:mt-4 flex items-center text-xs sm:text-sm">
                    <span class="text-yellow-600">Needs attention</span>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="relative overflow-hidden rounded-xl bg-white p-4 sm:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Delivered Today</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-gray-900">{{ $completedToday }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="relative overflow-hidden rounded-xl bg-white p-4 sm:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Revenue</p>
                        <p class="mt-1 text-2xl sm:text-3xl font-bold text-green-600">GH₵{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-4 sm:gap-6 md:grid-cols-3">
            <!-- Recent Orders -->
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm md:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">View all</a>
                </div>
                
                @if($recentOrders->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="rounded-full bg-gray-100 p-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first order.</p>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Order ID</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Customer</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 hidden sm:table-cell">Service</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="pb-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 text-sm font-semibold text-gray-900">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-3 text-sm text-gray-600">{{ $order->customer->name }}</td>
                                        <td class="py-3 text-gray-600 hidden sm:table-cell">
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
                                                            @case('deep_cleaning')
                                                                <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full">Ironing</span>
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
                                        <td class="py-3">
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
                                        <td class="py-3 text-sm font-semibold text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('orders.create') }}" class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Order
                    </a>
                    <a href="{{ route('customers.create') }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Add Customer
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        All Orders
                    </a>
                    <a href="{{ route('items.create') }}" class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Add Item
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
