<x-layouts::app :title="__('Branch Report - ') . $branch">
    <x-flash-message />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $branch }} - Branch Report</h1>
                <p class="text-zinc-400 mt-1">Detailed view of business performance</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-700 text-white rounded-lg hover:bg-zinc-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-zinc-300 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="px-3 py-2 border border-zinc-600 rounded-lg bg-zinc-700 text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-zinc-300 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="px-3 py-2 border border-zinc-600 rounded-lg bg-zinc-700 text-white focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Branch Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-4">
                <p class="text-sm text-zinc-400">Total Orders</p>
                <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total_orders']) }}</p>
            </div>
            <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-4">
                <p class="text-sm text-zinc-400">Completed</p>
                <p class="text-2xl font-bold text-emerald-400 mt-1">{{ number_format($stats['completed_orders']) }}</p>
            </div>
            <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-4">
                <p class="text-sm text-zinc-400">Total Revenue</p>
                <p class="text-2xl font-bold text-green-400 mt-1">GH₵{{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-4">
                <p class="text-sm text-zinc-400">Pending</p>
                <p class="text-2xl font-bold text-amber-400 mt-1">{{ number_format($stats['pending_orders']) }}</p>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-zinc-800 rounded-xl border border-zinc-700 overflow-hidden">
            <div class="p-4 border-b border-zinc-700">
                <h2 class="text-lg font-semibold text-white">Orders</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400">Amount</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400">Paid</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-zinc-700/30">
                            <td class="px-4 py-3">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-400 hover:text-blue-300">#{{ $order->id }}</a>
                            </td>
                            <td class="px-4 py-3 text-zinc-300">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if($order->status === 'completed')
                                    <span class="px-2 py-1 bg-emerald-900 text-emerald-400 rounded text-xs">Completed</span>
                                @elseif($order->status === 'pending')
                                    <span class="px-2 py-1 bg-amber-900 text-amber-400 rounded text-xs">Pending</span>
                                @elseif($order->status === 'in_progress')
                                    <span class="px-2 py-1 bg-blue-900 text-blue-400 rounded text-xs">In Progress</span>
                                @elseif($order->status === 'ready')
                                    <span class="px-2 py-1 bg-purple-900 text-purple-400 rounded text-xs">Ready</span>
                                @else
                                    <span class="px-2 py-1 bg-red-900 text-red-400 rounded text-xs">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-white">GH₵{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-green-400">GH₵{{ number_format($order->amount_paid, 2) }}</td>
                            <td class="px-4 py-3 text-zinc-400">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No orders found for this branch.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
    @media print {
        body { background: white !important; }
        .dark { background-color: white !important; }
        .dark\:bg-zinc-800 { background-color: white !important; }
        .dark\:text-white { color: #18181b !important; }
        button, a { display: none !important; }
    }
    </style>
</x-layouts::app>
