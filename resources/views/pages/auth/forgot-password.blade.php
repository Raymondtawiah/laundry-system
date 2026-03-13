<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Forgot Password' }}</title>
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
        <!-- Forgot Password Card -->
        <div class="bg-white/95 rounded-2xl shadow-2xl p-8">
            <!-- Logo/Brand -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Malsnuel Enterprise</h1>
                <p class="text-gray-500 mt-1">Laundry Management System</p>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-1">Forgot Password?</h2>
            <p class="text-gray-500 text-sm mb-5">Enter your email to receive a password reset link</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        required 
                        autofocus
                        placeholder="you@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    >
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-md hover:shadow-lg"
                >
                    Email Password Reset Link
                </button>
            </form>

            <!-- Back to Login -->
            <p class="mt-5 text-center text-gray-500">
                Or, return to 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    log in
                </a>
            </p>
        </div>
    </div>
</body>
</html>
