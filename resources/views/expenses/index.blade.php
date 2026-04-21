<x-layouts::app :title="__('Expenses')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Expenses</h1>
                <p class="text-sm text-gray-500">Track and manage expenses</p>
            </div>
            <button type="button" onclick="document.getElementById('add-expense-modal').showModal()" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Expense
            </button>
        </div>

        <!-- Filter -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search description..." 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="w-full sm:w-40">
                    <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                    <select name="branch" id="branch" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="" class="bg-white text-gray-900">All Branches</option>
                        <option value="Daasebre" {{ request('branch') === 'Daasebre' ? 'selected' : '' }} class="bg-white text-gray-900">Daasebre</option>
                        <option value="Nyamekrom" {{ request('branch') === 'Nyamekrom' ? 'selected' : '' }} class="bg-white text-gray-900">Nyamekrom</option>
                        <option value="KTU" {{ request('branch') === 'KTU' ? 'selected' : '' }} class="bg-white text-gray-900">KTU</option>
                    </select>
                </div>
                @endif
                <div class="w-full sm:w-40">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-40">
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <input type="month" name="month" id="month" value="{{ request('month') }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('date') || request('month') || request('branch'))
                    <a href="{{ route('expenses.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Expenses Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 font-medium">Date</th>
                            <th class="px-4 py-3 font-medium">Branch</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium text-right">Amount</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">{{ $expense->branch }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $expense->description ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">
                                    GH₵{{ number_format($expense->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @if(auth()->user()->role === 'admin')
                                        <button type="button" onclick="document.getElementById('edit-expense-modal-{{ $expense->id }}').showModal()" class="rounded-lg p-1.5 text-blue-500 hover:bg-blue-50 hover:text-blue-600" title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        @endif
                                        <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50 hover:text-red-600" title="Delete">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="mt-3 text-sm font-medium text-gray-900">No expenses found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by adding your first expense.</p>
                                        <button onclick="document.getElementById('add-expense-modal').showModal()" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                            Add Expense
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($expenses->hasPages())
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

    <!-- Add Expense Modal -->
    <dialog id="add-expense-modal" class="modal rounded-xl p-6 shadow-xl backdrop:bg-gray-900/50 bg-white text-gray-900">
        <div class="w-full max-w-md">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Add Expense</h2>
                <button type="button" onclick="document.getElementById('add-expense-modal').close()" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (GH₵)</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" required placeholder="0.00"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('amount')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" id="date" required value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <select name="branch" id="branch" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="" class="bg-white text-gray-900">Select Branch</option>
                            <option value="Daasebre" class="bg-white text-gray-900">Daasebre</option>
                            <option value="Nyamekrom" class="bg-white text-gray-900">Nyamekrom</option>
                            <option value="KTU" class="bg-white text-gray-900">KTU</option>
                        </select>
                        @error('branch')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="3" placeholder="Enter expense description..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="document.getElementById('add-expense-modal').close()" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Expense Modals (Admin Only) -->
    @if(auth()->user()->role === 'admin')
        @foreach($expenses as $expense)
        <dialog id="edit-expense-modal-{{ $expense->id }}" class="modal rounded-xl p-6 shadow-xl backdrop:bg-gray-900/50 bg-white text-gray-900">
            <div class="w-full max-w-md">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Edit Expense</h2>
                    <button type="button" onclick="document.getElementById('edit-expense-modal-{{ $expense->id }}').close()" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit-amount-{{ $expense->id }}" class="block text-sm font-medium text-gray-700 mb-1">Amount (GH₵)</label>
                            <input type="number" name="amount" id="edit-amount-{{ $expense->id }}" step="0.01" min="0.01" required value="{{ $expense->amount }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit-date-{{ $expense->id }}" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" id="edit-date-{{ $expense->id }}" required value="{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit-branch-{{ $expense->id }}" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                            <select name="branch" id="edit-branch-{{ $expense->id }}" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="Daasebre" {{ $expense->branch === 'Daasebre' ? 'selected' : '' }} class="bg-white text-gray-900">Daasebre</option>
                                <option value="Nyamekrom" {{ $expense->branch === 'Nyamekrom' ? 'selected' : '' }} class="bg-white text-gray-900">Nyamekrom</option>
                                <option value="KTU" {{ $expense->branch === 'KTU' ? 'selected' : '' }} class="bg-white text-gray-900">KTU</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-description-{{ $expense->id }}" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="edit-description-{{ $expense->id }}" rows="3"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ $expense->description }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" onclick="document.getElementById('edit-expense-modal-{{ $expense->id }}').close()" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
        @endforeach
    @endif
</x-layouts::app>