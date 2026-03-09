<x-layouts::app :title="__('Customers')">
    @php $branch = $branch ?? ''; @endphp
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Customers</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your customer database</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Branch Filter (Admin Only) -->
                @if(auth()->user()->role === 'admin')
                <form method="GET" action="{{ route('customers.index') }}" class="flex items-center">
                    <select 
                        name="branch" 
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">All Branches</option>
                        <option value="Daasebre" {{ $branch === 'Daasebre' ? 'selected' : '' }}>Daasebre</option>
                        <option value="Nyamekrom" {{ $branch === 'Nyamekrom' ? 'selected' : '' }}>Nyamekrom</option>
                        <option value="KTU" {{ $branch === 'KTU' ? 'selected' : '' }}>KTU</option>
                    </select>
                </form>
                @endif
            </div>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </a>
        </div>

        <!-- Customers Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:bg-gray-800 dark:border-gray-700">
            @if($customers->isEmpty())
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No customers yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding your first customer.</p>
                    <a href="{{ route('customers.create') }}" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Add Customer
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Branch</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Address</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customers as $customer)
                                <tr class="dark:hover:bg-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                        @if($customer->notes)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $customer->branch ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->email ?? '-' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">{{ $customer->address ?? '-' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 inline-flex mr-3" title="View Details">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 cursor-pointer" onclick="return confirm('Are you sure you want to delete this customer?')">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
