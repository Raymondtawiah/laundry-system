<x-layouts::app :title="__('Business Report')">
    <x-flash-message />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
    canvas {
        max-width: 100%;
    }
    .chart-container {
        background: transparent;
    }
</style>

<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Business Report</h1>
            <p class="text-zinc-600 dark:text-zinc-400 mt-1">Overview of business performance across all branches</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-lg hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-zinc-300 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="px-3 py-2 border border-zinc-600 rounded-lg bg-zinc-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-zinc-300 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="px-3 py-2 border border-zinc-600 rounded-lg bg-zinc-700 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Overall Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-400">Total Orders</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($overallStats['total_orders']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-400 mt-1">GH₵{{ number_format($overallStats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-400">Completed Orders</p>
                    <p class="text-2xl font-bold text-emerald-400 mt-1">{{ number_format($overallStats['completed_orders']) }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-900/50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-zinc-400">Pending Orders</p>
                    <p class="text-2xl font-bold text-amber-400 mt-1">{{ number_format($overallStats['pending_orders']) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-900/50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Branch Performance Charts -->
    <div class="grid grid-cols-1 gap-4 mb-6 print:hidden">
        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <h2 class="text-lg font-semibold text-white mb-3">Branch Performance Comparison - Revenue</h2>
            <canvas id="revenueChart" width="800" height="300"></canvas>
        </div>
    </div>

    <!-- Order Status Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 print:hidden">
        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <h2 class="text-lg font-semibold text-white mb-3">Order Status Distribution</h2>
            <div class="flex justify-center">
                <canvas id="orderStatusChart" width="200" height="200"></canvas>
            </div>
        </div>

        <!-- Payment Status Distribution -->
        <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 p-4">
            <h2 class="text-lg font-semibold text-white mb-3">Payment Status Distribution</h2>
            <div class="flex justify-center">
                <canvas id="paymentStatusChart" width="200" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Branch Performance Table -->
    <div class="bg-zinc-800 dark:bg-zinc-900 rounded-xl border border-zinc-700 overflow-hidden">
        <div class="p-4 border-b border-zinc-700">
            <h2 class="text-lg font-semibold text-white">Branch Performance</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-zinc-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Branch</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Orders</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Completed</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Revenue</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Unpaid</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Customers</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-zinc-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-700">
                    @forelse($branchStats as $stat)
                    <tr class="hover:bg-zinc-700/30">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-white">{{ $stat['branch'] }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-zinc-400">
                            {{ number_format($stat['total_orders']) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-emerald-400">
                            {{ number_format($stat['completed_orders']) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-white">
                            GH₵{{ number_format($stat['total_revenue'], 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-green-400">
                            GH₵{{ number_format($stat['total_paid'], 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-red-400">
                            GH₵{{ number_format($stat['total_unpaid'], 2) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm text-purple-400">
                            {{ number_format($stat['new_customers']) }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('reports.branch', ['branch' => $stat['branch']]) }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                                class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-zinc-500">
                            No branch data found. Please ensure staff members are assigned to branches.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-zinc-700/50">
                    <tr>
                        <td class="px-4 py-3 text-left text-sm font-bold text-white">Total</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-white">{{ number_format($overallStats['total_orders']) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-emerald-400">{{ number_format($overallStats['completed_orders']) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-white">GH₵{{ number_format($overallStats['total_revenue'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-green-400">GH₵{{ number_format($overallStats['total_paid'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-red-400">GH₵{{ number_format($overallStats['total_unpaid'], 2) }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-purple-400">{{ number_format($overallStats['total_customers']) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Chart Initialization -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const branchData = @json($branchStats);
    const branches = branchData.map(b => b.branch);
    const orders = branchData.map(b => b.total_orders);
    const revenues = branchData.map(b => parseFloat(b.total_revenue));
    const completedOrders = branchData.map(b => b.completed_orders);
    const pendingOrders = branchData.map(b => b.pending_orders);
    
    const statusBreakdown = @json($statusBreakdown);
    const paymentBreakdown = @json($paymentBreakdown);
    
    // Always use bright colors for dark background
    const textColor = '#e4e4e7';
    const gridColor = '#52525b';
    
    // Orders by Branch - Doughnut Chart
    const ordersCtx = document.getElementById('branchOrdersChart');
    if (ordersCtx) {
    new Chart(ordersCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: branches,
            datasets: [{
                label: 'Orders',
                data: orders,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor }
                }
            },
            scales: {
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: textColor },
                    grid: { color: gridColor },
                    beginAtZero: true
                }
            }
        }
    });
    }
    
    // Revenue by Branch - Doughnut Chart
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
    new Chart(revenueCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: branches,
            datasets: [{
                label: 'Revenue (GH₵)',
                data: revenues,
                borderColor: ['#3b82f6', '#10b981', '#f59e0b'],
                backgroundColor: 'transparent',
                tension: 0.4,
                pointBackgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                pointRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor }
                }
            },
            scales: {
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: textColor },
                    grid: { color: gridColor },
                    beginAtZero: true
                }
            }
        }
    });
    
    // Order Status - Doughnut Chart
    const orderStatusCanvas = document.getElementById('orderStatusChart');
    if (orderStatusCanvas) {
    new Chart(orderStatusCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending', 'In Progress', 'Ready', 'Cancelled'],
            datasets: [{
                data: [
                    statusBreakdown.completed,
                    statusBreakdown.pending,
                    statusBreakdown.in_progress,
                    statusBreakdown.ready,
                    statusBreakdown.cancelled
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor }
                }
            }
        }
    });
    
    // Payment Status - Doughnut Chart
    const paymentStatusCanvas = document.getElementById('paymentStatusChart');
    if (paymentStatusCanvas) {
    new Chart(paymentStatusCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Fully Paid', 'Partial', 'Unpaid'],
            datasets: [{
                data: [
                    paymentBreakdown.paid || 0,
                    paymentBreakdown.partial || 0,
                    paymentBreakdown.unpaid || 0
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor }
                }
            }
        }
    });
    }
    }
});
</script>

<style>
@media print {
    body { background: white !important; }
    .dark { --tw-bg-opacity: 1 !important; background-color: white !important; }
    .dark\:bg-zinc-800, .dark\:bg-zinc-700, .dark\:bg-zinc-700\/50 { background-color: white !important; }
    .dark\:text-white { color: #18181b !important; }
    .dark\:text-zinc-400, .dark\:text-zinc-300 { color: #52525b !important; }
    button { display: none !important; }
    .print\\:hidden { display: none !important; }
}
</style>
</x-layouts::app>
