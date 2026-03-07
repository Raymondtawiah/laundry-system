<x-layouts::app :title="__('Staff Management')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Staff Management</h1>
                <p class="text-sm text-zinc-400">Manage your laundry staff members</p>
            </div>
            <a href="{{ route('staff.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Staff
            </a>
        </div>

        <!-- Staff List -->
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            @if($staff->isEmpty())
                <div class="text-center py-8">
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    <p class                    </div>
-400 mb-="text-zinc                        </svg>
4">No staff members found</p>
                    <a href="{{ route('staff.create') }}" class="text-blue-400 hover:text-blue-300">Add your first staff member</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-zinc-700">
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-400">Name</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-400">Email</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-400">Role</th>
                                <th class="pb-3 text-left text-xs font-medium uppercase tracking-wider text-zinc-400">Status</th>
                                <th class="pb-3 text-right text-xs font-medium uppercase tracking-wider text-zinc-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-700">
                            @foreach($staff as $member)
                                <tr>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-700 text-white text-sm font-medium">
                                                {{ strtoupper(substr($member->name, 0, 2)) }}
                                            </div>
                                            <span class="text-white font-medium">{{ $member->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-zinc-400">{{ $member->email }}</td>
                                    <td class="py-4">
                                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Staff
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        @if($member->is_approved)
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <form action="{{ route('staff.destroy', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Are you sure you want to delete this staff member?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
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
