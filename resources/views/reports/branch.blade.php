<x-layouts::app :title="__('Branch Report')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Branch Report - {{ $branch }}</h1>
                <p class="text-sm text-gray-500">{{ $startDate }} to {{ $endDate }}</p>
            </div>
            <div class="flex gap-2 print:hidden">
                <button onclick="window.print()" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Report
                </button>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm print:hidden">
            <form method="GET" action="{{ route('reports.branch', $branch) }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="w-full sm:w-48">
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                    <select name="period" id="period" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Branch Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&status=completed" class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-green-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['completed_orders'] }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&delivery=doorstep" class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-purple-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Pickups</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['deliveries'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-amber-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Revenue</p>
                        <p class="text-xl sm:text-2xl font-bold text-green-600">GH₵{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-blue-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Payment Breakdown -->
        @if(isset($customerStats))
        <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Client Payment Status</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&client_payment=paid" class="flex items-center gap-3 p-3 rounded-lg border {{ $clientPaymentFilter === 'paid' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300 hover:bg-green-50' }} transition-all">
                    <span class="h-3 w-3 rounded-full bg-green-500"></span>
                    <div>
                        <p class="text-sm text-gray-600">Full Payment</p>
                        <p class="text-lg font-bold text-gray-900">{{ $customerStats['full_payment'] }}</p>
                    </div>
                </a>
                <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&client_payment=partial" class="flex items-center gap-3 p-3 rounded-lg border {{ $clientPaymentFilter === 'partial' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300 hover:bg-yellow-50' }} transition-all">
                    <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                    <div>
                        <p class="text-sm text-gray-600">Part Payment</p>
                        <p class="text-lg font-bold text-gray-900">{{ $customerStats['part_payment'] }}</p>
                    </div>
                </a>
                <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&client_payment=with_balance" class="flex items-center gap-3 p-3 rounded-lg border {{ $clientPaymentFilter === 'with_balance' ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300 hover:bg-orange-50' }} transition-all">
                    <span class="h-3 w-3 rounded-full bg-orange-500"></span>
                    <div>
                        <p class="text-sm text-gray-600">With Balance</p>
                        <p class="text-lg font-bold text-gray-900">{{ $customerStats['with_balance'] }}</p>
                    </div>
                </a>
                <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}&client_payment=unpaid" class="flex items-center gap-3 p-3 rounded-lg border {{ $clientPaymentFilter === 'unpaid' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }} transition-all">
                    <span class="h-3 w-3 rounded-full bg-red-500"></span>
                    <div>
                        <p class="text-sm text-gray-600">Unpaid</p>
                        <p class="text-lg font-bold text-gray-900">{{ $customerStats['with_balance'] - $customerStats['part_payment'] }}</p>
                    </div>
                </a>
            </div>
            @if($clientPaymentFilter)
                <div class="mt-4 flex items-center justify-between print:hidden">
                    <a href="{{ route('reports.branch', $branch) }}?period={{ $period }}" class="text-sm text-blue-600 hover:text-blue-800">
                        ← Clear filter
                    </a>
                    <span class="text-sm text-gray-500">{{ count($clientList) }} clients found</span>
                </div>
            @endif
        </div>
        @endif

        <!-- Client List with Payment Status -->
        @if($clientPaymentFilter && isset($clientList) && count($clientList) > 0)
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    @switch($clientPaymentFilter)
                        @case('paid')
                            Clients with Full Payment
                            @break
                        @case('partial')
                            Clients with Partial Payment
                            @break
                        @case('with_balance')
                            Clients with Balance
                            @break
                        @case('unpaid')
                            Clients with Unpaid Orders
                            @break
                    @endswitch
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Orders</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Paid</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Balance</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($clientList as $client)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $client['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $client['phone'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $client['total_orders'] }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">GH₵{{ number_format($client['total_amount'], 2) }}</td>
                                <td class="px-4 py-3 text-sm text-green-600">GH₵{{ number_format($client['total_paid'], 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium {{ $client['total_balance'] > 0 ? 'text-red-600' : 'text-gray-600' }}">GH₵{{ number_format($client['total_balance'], 2) }}</td>
                                <td class="px-4 py-3">
                                    @if($client['status'] === 'paid')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Paid</span>
                                    @elseif($client['status'] === 'partial')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Partial</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Orders Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            @if($orders->isEmpty())
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-gray-100 p-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-medium text-gray-900">No orders found</h3>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Order ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $order->customer->name ?? 'N/A' }}</td>
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
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Paid</span>
                                        @elseif($order->payment_status === 'partial')
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700">Partial</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">GH₵{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
