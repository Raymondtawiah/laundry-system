<x-layouts::auth :title="__('Verify Your Account')">
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ __('A 6-digit verification code has been sent to your email address.') }}
        </flux:text>

        @if (session('status'))
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ session('status') }}
            </flux:text>
        @endif

        @if (session('error'))
            <flux:text class="text-center font-medium !dark:text-red-400 !text-red-600">
                {{ session('error') }}
            </flux:text>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}" class="flex flex-col gap-4">
            @csrf
            
            <div class="flex flex-col gap-2">
                <flux:label for="code">{{ __('Verification Code') }}</flux:label>
                <flux:input 
                    type="text" 
                    name="code" 
                    id="code" 
                    placeholder="000000" 
                    maxlength="6" 
                    required 
                    autofocus
                    class="text-center text-2xl tracking-widest"
                />
                @error('code')
                    <flux:text class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Verify') }}
            </flux:button>
        </form>

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <flux:button type="submit" variant="ghost" class="text-sm">
                    {{ __('Resend code') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
