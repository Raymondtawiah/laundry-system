<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head')
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(40px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes pulse-ring {
                0% { transform: scale(0.8); opacity: 0.8; }
                50% { transform: scale(1.2); opacity: 0.3; }
                100% { transform: scale(0.8); opacity: 0.8; }
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }

            .glass-nav {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .animate-slide-up {
                animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }

            .animate-float {
                animation: float 8s ease-in-out infinite;
            }

            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
            .delay-400 { animation-delay: 400ms; }
            .delay-500 { animation-delay: 500ms; }
            .delay-600 { animation-delay: 600ms; }

            .gradient-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.6;
                pointer-events: none;
            }

            .text-gradient {
                background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 50%, #fce7f3 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .btn-primary {
                background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
                color: #4f46e5;
                font-weight: 700;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.5);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .btn-primary:hover {
                transform: translateY(-2px) scale(1.02);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.5);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                font-weight: 600;
                border: 1px solid rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-2px);
                border-color: rgba(255, 255, 255, 0.5);
            }

            .feature-card {
                background: rgba(255, 255, 255, 0.06);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .feature-card:hover {
                background: rgba(255, 255, 255, 0.12);
                transform: translateY(-8px);
                border-color: rgba(255, 255, 255, 0.3);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            }

            .service-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                transition: all 0.3s ease;
            }

            .service-card:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: scale(1.05);
            }

            .nav-link {
                position: relative;
                transition: color 0.2s ease;
            }

            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 0;
                height: 2px;
                background: white;
                transition: width 0.3s ease;
            }

            .nav-link:hover::after {
                width: 100%;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                overflow-x: hidden;
            }
        </style>
    </head>
    <body class="min-h-screen antialiased text-white">
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="gradient-orb w-[600px] h-[600px] bg-blue-500/40 top-[-200px] left-[-100px] animate-float"></div>
            <div class="gradient-orb w-[500px] h-[500px] bg-purple-500/40 bottom-[-150px] right-[-100px] animate-float" style="animation-delay: 2s;"></div>
            <div class="gradient-orb w-[400px] h-[400px] bg-pink-500/30 top-[40%] left-[50%] animate-float" style="animation-delay: 4s;"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950/90 via-blue-900/80 to-purple-900/90"></div>
        </div>

        <div class="relative z-10">
            <header class="sticky top-0 z-50 w-full px-4 md:px-6 py-4">
                <nav class="max-w-7xl mx-auto glass-nav px-6 md:px-8 py-4 rounded-2xl shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center shadow-lg ring-2 ring-white/20">
                                <img src="{{ asset('logo.jpg') }}" alt="Logo" class="w-9 h-9 object-contain">
                            </div>
                            <span class="text-xl font-extrabold text-white tracking-tight">Malsnuel Enterprise</span>
                        </div>

                        <div class="flex items-center gap-4 md:gap-6">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-primary px-6 py-2.5 rounded-xl text-sm">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="nav-link text-sm font-semibold text-white/80 hover:text-white hidden sm:block">
                                    Log in
                                </a>
                                @if(\App\Models\User::where('role', 'admin')->doesntExist())
                                    <a href="{{ route('register') }}" class="btn-secondary px-6 py-2.5 rounded-xl text-sm">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </nav>
            </header>

            <main>
                <section class="relative min-h-[90vh] flex items-center justify-center px-6 pt-24 pb-16">
                    <div class="max-w-7xl mx-auto text-center">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white text-xs font-bold mb-8 animate-slide-up border border-white/20 shadow-lg">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
                            </span>
                            PROFESSIONAL LAUNDRY SOLUTIONS
                        </div>

                        <h1 class="text-5xl sm:text-7xl lg:text-8xl font-black mb-8 tracking-tight animate-slide-up delay-100 leading-[1.1]">
                            Laundry <span class="text-gradient">Management</span>
                            <br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200">Redefined</span>
                        </h1>

                        <p class="max-w-2xl mx-auto text-lg md:text-xl text-white/70 mb-12 animate-slide-up delay-200 leading-relaxed">
                            Streamline your operations, track every order, and grow your business with our all-in-one management platform.
                        </p>

                        @guest
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up delay-300">
                                @if(\App\Models\User::where('role', 'admin')->doesntExist())
                                    <a href="{{ route('register') }}" class="btn-primary px-10 py-4 rounded-2xl text-lg">
                                        Get Started
                                    </a>
                                @endif
                                <a href="{{ route('login') }}" class="btn-secondary px-10 py-4 rounded-2xl text-lg">
                                    Sign In
                                </a>
                            </div>
                        @endguest
                    </div>
                </section>

                <section class="py-24 px-6">
                    <div class="max-w-7xl mx-auto">
                        <div class="text-center mb-20">
                            <p class="text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4 animate-slide-up">Why Choose Us</p>
                            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-slide-up delay-100">
                                Everything you need
                            </h2>
                            <p class="text-white/60 text-lg max-w-2xl mx-auto animate-slide-up delay-200">
                                Powerful features designed to simplify your laundry business operations.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-3 gap-8">
                            <div class="group feature-card p-8 rounded-[2rem] animate-slide-up delay-200">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mb-8 shadow-xl group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4">Order Management</h3>
                                <p class="text-white/60 leading-relaxed text-[15px]">Efficiently track every order with real-time status updates and automated notifications.</p>
                            </div>

                            <div class="group feature-card p-8 rounded-[2rem] animate-slide-up delay-300">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mb-8 shadow-xl group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4">Revenue Tracking</h3>
                                <p class="text-white/60 leading-relaxed text-[15px]">Monitor your business performance with detailed financial reports and live analytics.</p>
                            </div>

                            <div class="group feature-card p-8 rounded-[2rem] animate-slide-up delay-400">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center mb-8 shadow-xl group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-4">Customer Loyalty</h3>
                                <p class="text-white/60 leading-relaxed text-[15px]">Build strong relationships with integrated history tracking and personalized preferences.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- About Section -->
                <section class="py-24 px-6">
                    <div class="max-w-7xl mx-auto">
                        <div class="text-center mb-20">
                            <p class="text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4 animate-slide-up">About Us</p>
                            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-slide-up delay-100">
                                About Malsnuel Enterprise
                            </h2>
                            <p class="text-white/60 text-lg max-w-3xl mx-auto animate-slide-up delay-200">
                                We provide professional laundry management solutions that streamline your operations and help grow your business.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="animate-slide-up delay-300">
                                <h3 class="text-2xl font-bold text-white mb-4">Our Story</h3>
                                <p class="text-white/70 mb-4 leading-relaxed">
                                    Founded in 2020, Malsnuel Enterprise has been serving the community with reliable and efficient laundry services. Our mission is to provide top-quality garment care while using eco-friendly practices.
                                </p>
                                <p class="text-white/70 leading-relaxed">
                                    With our state-of-the-art management system, we ensure every garment is tracked from pickup to delivery, giving you peace of mind and convenience.
                                </p>
                            </div>
                            <div class="glass-card rounded-[2rem] p-8 animate-slide-up delay-400">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-white">Fast & Reliable</h4>
                                </div>
                                <p class="text-white/60 mb-6">Same-day service with 24-hour guarantee</p>
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-white">Quality Guaranteed</h4>
                                </div>
                                <p class="text-white/60">100% satisfaction or we rewash for free</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Services Section -->
                <section class="py-24 px-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/5 to-transparent"></div>
                    <div class="max-w-7xl mx-auto relative">
                        <div class="text-center mb-20">
                            <p class="text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4 animate-slide-up">Our Services</p>
                            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-slide-up delay-100">
                                Premium laundry services
                            </h2>
                            <p class="text-white/60 text-lg max-w-2xl mx-auto animate-slide-up delay-200">
                                Everything you need to provide a top-tier laundry experience.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach([
                                ['title' => 'Wash & Fold', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                                ['title' => 'Dry Cleaning', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                                ['title' => 'Ironing Service', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                ['title' => 'Pickup & Delivery', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4']
                            ] as $service)
                                <div class="service-card flex flex-col items-center gap-5 p-8 rounded-3xl animate-slide-up delay-{{ ($loop->index + 2) * 100 }}">
                                    <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center backdrop-blur-md border border-white/10">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <span class="text-white font-bold text-lg tracking-wide">{{ $service['title'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- FAQ Section -->
                <section class="py-24 px-6">
                    <div class="max-w-4xl mx-auto">
                        <div class="text-center mb-20">
                            <p class="text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4 animate-slide-up">FAQ</p>
                            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-slide-up delay-100">
                                Frequently Asked Questions
                            </h2>
                            <p class="text-white/60 text-lg max-w-2xl mx-auto animate-slide-up delay-200">
                                Find answers to common questions about our services.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="glass-card rounded-2xl p-6 animate-slide-up delay-300" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-lg font-bold text-white">What are your service hours?</h3>
                                    <svg class="w-5 h-5 text-white transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-4 text-white/60">
                                    <p>We are open Monday to Saturday from 7:00 AM to 8:00 PM. Sunday hours are 9:00 AM to 5:00 PM.</p>
                                </div>
                            </div>

                            <div class="glass-card rounded-2xl p-6 animate-slide-up delay-400" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-lg font-bold text-white">How long does laundry take?</h3>
                                    <svg class="w-5 h-5 text-white transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-4 text-white/60">
                                    <p>Standard laundry services are completed within 24 hours. Express service available for same-day delivery.</p>
                                </div>
                            </div>

                            <div class="glass-card rounded-2xl p-6 animate-slide-up delay-500" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-lg font-bold text-white">Do you offer pickup and delivery?</h3>
                                    <svg class="w-5 h-5 text-white transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-4 text-white/60">
                                    <p>Yes, we offer both pickup and doorstep delivery services. Select your preferred option when placing an order.</p>
                                </div>
                            </div>

                            <div class="glass-card rounded-2xl p-6 animate-slide-up delay-600" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-lg font-bold text-white">What payment methods do you accept?</h3>
                                    <svg class="w-5 h-5 text-white transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-4 text-white/60">
                                    <p>We accept cash, mobile money (MTN, Vodafone, AirtelTigo), and bank transfers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact Section -->
                <section class="py-24 px-6">
                    <div class="max-w-4xl mx-auto">
                        <div class="text-center mb-20">
                            <p class="text-sm font-semibold text-indigo-300 tracking-widest uppercase mb-4 animate-slide-up">Contact</p>
                            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 animate-slide-up delay-100">
                                Get in Touch
                            </h2>
                            <p class="text-white/60 text-lg max-w-2xl mx-auto animate-slide-up delay-200">
                                Have questions? We're here to help!
                            </p>
                        </div>

                        <div class="glass-card rounded-[2rem] p-8 md:p-12">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-6">Contact Information</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-white/70">Daasebre, Nyamekrom, KTU Branches</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.5a1 1 0 01-.502 1.21L6.714 10.997a8.021 8.021 0 002.726 2.726l1.21-1.528a1 1 0 011.21-.502l4.5-1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-white/70">+233 50 123 4567</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L23 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-white/70">info@malsnuel.com</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-6">Send a Message</h3>
                                    <form class="space-y-4">
                                        <div>
                                            <input type="text" placeholder="Your name" class="w-full rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-blue-400">
                                        </div>
                                        <div>
                                            <input type="email" placeholder="Email address" class="w-full rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-blue-400">
                                        </div>
                                        <div>
                                            <textarea placeholder="Your message" rows="3" class="w-full rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-blue-400"></textarea>
                                        </div>
                                        <button type="submit" class="w-full btn-primary py-3 rounded-xl font-bold">Send Message</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>

            <footer class="border-t border-white/10 mt-12">
                <div class="max-w-7xl mx-auto px-6 py-10">
                        <p class="text-sm text-white/50 text-center">&copy; {{ date('Y') }} Laundry Management System. All rights reserved.</p>
                </div>
            </footer>
        </div>

        @fluxScripts
        <x-flash-message />
    </body>
</html>
