<x-layouts::app :title="__('Reports')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
                <p class="text-sm text-gray-500">View and analyze your laundry business performance</p>
            </div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Reports
            </a>
        </div>

        <!-- Filter Form -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="w-full sm:w-48">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-48">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Apply Filter
                </button>
            </form>
        </div>

        <!-- Overall Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-blue-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Orders</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $overallStats['total_orders'] }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-green-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $overallStats['completed_orders'] }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-purple-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Revenue</p>
                        <p class="text-xl sm:text-2xl font-bold text-green-600">GH₵{{ number_format($overallStats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-amber-100 p-2 sm:p-3">
                        <svg class="h-5 sm:h-6 w-5 sm:w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Customers</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $overallStats['total_customers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch Stats -->
        @if($branchStats->isNotEmpty())
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($branchStats as $stat)
                <a href="{{ route('reports.branch', $stat['branch']) }}" class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $stat['branch'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $stat['total_orders'] }} orders</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Completed</span>
                            <span class="text-sm font-semibold text-green-600">{{ $stat['completed_orders'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Revenue</span>
                            <span class="text-sm font-semibold text-gray-900">GH₵{{ number_format($stat['total_revenue'], 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Paid</span>
                            <span class="text-sm font-semibold text-green-600">GH₵{{ number_format($stat['total_paid'], 2) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @else
        <div class="rounded-xl border border-gray-200 bg-white p-12 shadow-sm">
            <div class="flex flex-col items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No branches found</h3>
                <p class="mt-1 text-sm text-gray-500">Create a branch to see reports.</p>
            </div>
        </div>
        @endif
    </div>
</x-layouts::app>
