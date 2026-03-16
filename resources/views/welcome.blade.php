<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <!-- Fallback CDN for development -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.7; }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out forwards;
            }
            .animate-fade-in {
                animation: fadeIn 1s ease-out forwards;
            }
            .animate-slide-left {
                animation: slideInLeft 0.8s ease-out forwards;
            }
            .animate-slide-right {
                animation: slideInRight 0.8s ease-out forwards;
            }
            .animate-pulse-slow {
                animation: pulse 3s ease-in-out infinite;
            }
            .delay-100 { animation-delay: 0.1s; }
            .delay-200 { animation-delay: 0.2s; }
            .delay-300 { animation-delay: 0.3s; }
            .delay-400 { animation-delay: 0.4s; }
            .delay-500 { animation-delay: 0.5s; }
        </style>
    </head>
    <body class="min-h-screen bg-zinc-900 flex flex-col p-6 relative overflow-y-auto">
        <!-- Background Image -->
        <div class="fixed inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1545173168-9f1947eebb7f?w=1920&q=80" alt="Laundry" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-zinc-900/70"></div>
        </div>
        
        <div class="w-full max-w-4xl relative z-10 mx-auto">
            <!-- Header -->
            <header class="flex items-center justify-between mb-12 opacity-0 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo" class="h-10 w-10 object-contain rounded-lg">
                    <span class="text-xl font-semibold text-white">Malsnuel Enterprise</span>
                </div>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-zinc-300 hover:text-white transition-colors">
                            Log in
                        </a>
                        @if(\App\Models\User::where('role', 'admin')->doesntExist())
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Get Started
                        </a>
                        @endif
                    @endauth
                </nav>
            </header>

            <!-- Hero -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 opacity-0 animate-fade-in-up" style="animation-delay: 0.3s;">Laundry Management System</h1>
                <p class="text-lg text-zinc-400 mb-8 opacity-0 animate-fade-in-up" style="animation-delay: 0.4s;">Professional laundry management for your business</p>
                @guest
                <div class="flex items-center justify-center gap-4 opacity-0 animate-fade-in-up" style="animation-delay: 0.5s;">
                    @if(\App\Models\User::where('role', 'admin')->doesntExist())
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all transform hover:scale-105 hover:shadow-lg">
                        Get Started
                    </a>
                    @endif
                    <a href="{{ route('login') }}" class="px-6 py-3 border border-zinc-700 hover:bg-zinc-800 text-zinc-300 rounded-lg font-medium transition-all transform hover:scale-105">
                        Sign In
                    </a>
                </div>
                @endguest
            </div>

            <!-- Features -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-zinc-800 rounded-xl p-6 opacity-0 animate-slide-left" style="animation-delay: 0.6s;">
                    <div class="w-10 h-10 rounded-lg bg-blue-600/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white mb-2">Order Management</h3>
                    <p class="text-sm text-zinc-400">Track and manage all laundry orders efficiently with real-time status updates.</p>
                </div>

                <div class="bg-zinc-800 rounded-xl p-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.7s;">
                    <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white mb-2">Revenue Tracking</h3>
                    <p class="text-sm text-zinc-400">Monitor your business income with detailed financial reports and analytics.</p>
                </div>

                <div class="bg-zinc-800 rounded-xl p-6 opacity-0 animate-slide-right" style="animation-delay: 0.8s;">
                    <div class="w-10 h-10 rounded-lg bg-purple-600/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white mb-2">Customer Management</h3>
                    <p class="text-sm text-zinc-400">Keep track of your customers with their laundry history and preferences.</p>
                </div>
            </div>

            <!-- Services -->
            <div class="bg-zinc-800 rounded-xl p-8 opacity-0 animate-fade-in" style="animation-delay: 0.9s;">
                <h2 class="text-xl font-semibold text-white mb-6 text-center">Our Services</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-zinc-700 hover:bg-zinc-600 transition-colors">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <span class="text-white">Wash & Fold</span>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-zinc-700 hover:bg-zinc-600 transition-colors">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        <span class="text-white">Dry Cleaning</span>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-zinc-700 hover:bg-zinc-600 transition-colors">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-white">Ironing Service</span>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-zinc-700 hover:bg-zinc-600 transition-colors">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span class="text-white">Pickup & Delivery</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-12 text-center text-sm text-zinc-500 opacity-0 animate-fade-in" style="animation-delay: 1s;">
                <p>&copy; {{ date('Y') }} Laundry Management System. All rights reserved.</p>
            </footer>
        </div>
        @fluxScripts
    </body>
    <x-flash-message />
</html>
