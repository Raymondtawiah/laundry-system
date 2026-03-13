<x-layouts::app :title="__('Register Staff')">
    <x-flash-message />
    
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Register Staff</h1>
                <p class="text-sm text-gray-500">Add a new staff member to your laundry</p>
            </div>
            <a href="{{ route('staff.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Staff
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">New Staff Member</h2>
                            <p class="text-sm text-gray-500">Fill in the staff details below</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('staff.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:input 
                                    name="name" 
                                    label="Staff Name" 
                                    type="text" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Enter full name"
                                />
                            </div>

                            <div>
                                <flux:input 
                                    name="email" 
                                    label="Email Address" 
                                    type="email" 
                                    required 
                                    autocomplete="email"
                                    placeholder="Enter email address"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:input 
                                    name="phone" 
                                    label="Phone Number" 
                                    type="tel" 
                                    autocomplete="tel"
                                    placeholder="Enter phone number"
                                />
                            </div>

                            <div>
                                <label for="branch" class="block text-sm font-medium text-gray-700 mb-2">Branch <span class="text-red-500">*</span></label>
                                <select 
                                    name="branch" 
                                    id="branch" 
                                    required 
                                    class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 py-3 px-4 text-base"
                                >
                                    <option value="" class="bg-white text-gray-900">Select a branch</option>
                                    <option value="Daasebre" class="bg-white text-gray-900">Daasebre</option>
                                    <option value="Nyamekrom" class="bg-white text-gray-900">Nyamekrom</option>
                                    <option value="KTU" class="bg-white text-gray-900">KTU</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <flux:input 
                                    name="password" 
                                    label="Password" 
                                    type="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 characters"
                                    viewable
                                />
                            </div>

                            <div>
                                <flux:input 
                                    name="password_confirmation" 
                                    label="Confirm Password" 
                                    type="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Re-enter password"
                                    viewable
                                />
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                            <flux:button type="submit" variant="primary">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Register Staff
                            </flux:button>
                            
                            <a href="{{ route('staff.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Quick Tips</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Staff will receive login credentials via email</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Each staff member is assigned to a branch</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="h-5 w-5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Password must be at least 8 characters</span>
                        </li>
                    </ul>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Staff Roles</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Admin</span>
                            <span class="text-sm text-gray-600">Full access</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">Manager</span>
                            <span class="text-sm text-gray-600">Manage orders</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">Staff</span>
                            <span class="text-sm text-gray-600">Basic access</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
