<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Verify Your Account' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            min-height: 100vh;
        }
        .bg-laundry {
            background-image: url('https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="bg-laundry flex items-center justify-center min-h-screen p-4">
    <!-- Dark overlay with small blur -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]"></div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Verification Card -->
        <div class="bg-white/95 rounded-2xl shadow-2xl p-8">
            <!-- Logo/Brand -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Malsnuel Enterprise</h1>
                <p class="text-gray-500 mt-1">Laundry Management System</p>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-1">Verify Your Account</h2>
            <p class="text-gray-500 text-sm mb-5">Enter the 6-digit code sent to your email</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}" class="space-y-5">
                @csrf
                
                <!-- Verification Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input 
                        type="text" 
                        name="code" 
                        id="code"
                        maxlength="6" 
                        required 
                        autofocus
                        placeholder="000000"
                        class="w-full text-center text-3xl font-mono tracking-[0.5em] px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        style="letter-spacing: 0.5em;"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg"
                >
                    Verify
                </button>
            </form>

            <!-- Resend & Logout -->
            <div class="mt-5 flex flex-col items-center gap-2">
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Resend code
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        input::placeholder {
            letter-spacing: 0.5em;
            text-align: center;
        }
    </style>
</body>
</html>
