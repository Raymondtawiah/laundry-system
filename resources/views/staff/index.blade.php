<x-layouts::app :title="__('Staff')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Staff</h1>
                <p class="text-sm text-gray-500">Manage your team members</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('staff.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Add Staff
                </a>
            @endif
        </div>

        <!-- Search -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('staff.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-40">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="" class="bg-white text-gray-900">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }} class="bg-white text-gray-900">Admin</option>
                        <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }} class="bg-white text-gray-900">Manager</option>
                        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }} class="bg-white text-gray-900">Staff</option>
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    Filter
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('staff.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Staff Table -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            @if($staff->isEmpty())
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="rounded-full bg-gray-100 p-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-3 text-sm font-medium text-gray-900">No staff found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first team member.</p>
                    <a href="{{ route('staff.create') }}" class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        Add Staff
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Staff Member</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 hidden sm:table-cell">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 hidden md:table-cell">Branch</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 hidden lg:table-cell">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 hidden lg:table-cell">Joined</th>
                                <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($staff as $member)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-medium text-sm">
                                                {{ substr($member->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $member->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell">
                                        @switch($member->role)
                                            @case('admin')
                                                <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Admin</span>
                                                @break
                                            @case('manager')
                                                <span class="inline-flex rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">Manager</span>
                                                @break
                                            @case('staff')
                                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">Staff</span>
                                                @break
                                            @default
                                                <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">{{ ucfirst($member->role) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        @if($member->laundry)
                                            <span class="text-sm text-gray-600">{{ $member->laundry->name }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell">
                                        <span class="text-sm text-gray-600">{{ $member->phone ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell">
                                        <span class="text-sm text-gray-500">{{ $member->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            @if(auth()->user()->role === 'admin' && $member->id !== auth()->id())
                                                <button type="button" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors" title="Remove" onclick="openDeleteModal{{ $member->id }}()">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                                <x-confirm-dialog 
                                                    :id="$member->id" 
                                                    :title="'Remove Staff Member'" 
                                                    :message="'Are you sure you want to remove ' . $member->name . '? This action cannot be undone.'" 
                                                    :action="route('staff.destroy', $member->id)"
                                                    :confirmText="'Delete'"
                                                />
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($staff->hasPages())
                    <div class="border-t border-gray-200 px-4 py-3">
                        {{ $staff->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-layouts::app>
