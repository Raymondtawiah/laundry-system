<x-layouts::app :title="__('Register Staff')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="rounded-xl border border-zinc-700 bg-zinc-800 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">Register New Staff</h2>
                    <p class="text-sm text-zinc-400">Add a new staff member to your laundry</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('staff.store') }}" class="space-y-6">
                @csrf

                <div>
                    <flux:input 
                        name="name" 
                        label="Staff Name" 
                        type="text" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Enter staff name"
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
                    <flux:input 
                        name="password" 
                        label="Password" 
                        type="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Enter password"
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
                        placeholder="Confirm password"
                        viewable
                    />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        Register Staff
                    </flux:button>
                    
                    <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
