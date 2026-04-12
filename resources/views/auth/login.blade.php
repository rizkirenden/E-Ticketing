@extends('layouts.app')

@section('title', 'Login - E-Ticketing System')

@section('content')
    <div class="min-h-screen bg-custom-dark flex items-center justify-center p-4 relative overflow-y-auto scroll-smooth"
        x-data="{ showPassword: false, loading: false }" style="height: 100vh;">

        <!-- Custom Scrollbar Styling -->
        <style>
            /* Custom Scrollbar untuk seluruh halaman */
            .overflow-y-auto::-webkit-scrollbar {
                width: 8px;
            }

            .overflow-y-auto::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.5), rgba(139, 92, 246, 0.5));
                border-radius: 10px;
                transition: all 0.3s ease;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 92, 246, 0.8));
            }

            /* Sembunyikan kolom kanan di mobile */
            @media (max-width: 768px) {
                .desktop-only {
                    display: none !important;
                }
            }
        </style>

        <!-- Background Security Pattern -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none opacity-5">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M30 5L55 20L55 40L30 55L5 40L5 20L30 5Z\' stroke=\'%234299e1\' fill=\'none\' stroke-width=\'0.5\'/%3E%3C/svg%3E'); background-size: 60px 60px;">
            </div>
        </div>

        <!-- Container Utama -->
        <div
            class="max-w-6xl w-full bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/20 relative z-10 my-8">
            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri - Form Login -->
                <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                    <div class="max-w-md mx-auto w-full">
                        <!-- Security Badge -->
                        <div class="flex justify-center md:justify-start mb-4">
                            <div
                                class="flex items-center space-x-2 bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs border border-green-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                <span>Secure Login (HTTPS + Encrypted)</span>
                            </div>
                        </div>

                        <!-- Header Form -->
                        <div class="text-center md:text-left mb-8">
                            <h2 class="text-3xl font-bold text-white mb-2 text-center">Welcome Back! 👋</h2>
                            <p class="text-gray-300 text-center">Secure access to IT HelpDesk system</p>
                        </div>

                        <!-- Form Login -->
                        <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-6" id="loginForm">
                            @csrf

                            <!-- Username Input -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-300 block flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Username
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="text" name="username" value="{{ old('username') }}"
                                        placeholder="Enter username"
                                        class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20 transition-all @error('username') border-red-500 @enderror"
                                        required minlength="3" maxlength="50" pattern="[a-zA-Z0-9_]+"
                                        title="Username can only contain letters, numbers, and underscores">
                                    @error('username')
                                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-300 block flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    Password
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                    </span>

                                    <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                        value="" placeholder="Enter password"
                                        class="w-full pl-12 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20 transition-all @error('password') border-red-500 @enderror"
                                        required minlength="8" autocomplete="current-password">

                                    <button type="button" @click="showPassword = !showPassword"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors focus:outline-none"
                                        tabindex="-1">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Google reCAPTCHA v3 -->
                            <input type="hidden" name="g-recaptcha-response" id="recaptcha-token">

                            <!-- Login Button -->
                            <button type="submit" :disabled="loading"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400/20 transform hover:scale-[1.02] transition-all duration-300 shadow-lg shadow-blue-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading">Sign In Securely 🔒</span>
                                <span x-show="loading" x-cloak>Processing... ⏳</span>
                            </button>

                            <!-- Back Button -->
                            <a href="{{ url('/') }}"
                                class="w-full flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-semibold py-3 px-4 rounded-xl border border-white/10 transform hover:scale-[1.02] transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Home
                            </a>
                        </form>

                        <!-- Security Info -->
                        <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                <span>Your connection is encrypted and secure. All activities are logged for security
                                    purposes.</span>
                            </div>
                        </div>

                        <!-- Session Messages -->
                        @if (session('success'))
                            <div class="mt-4 p-3 bg-green-500/20 border border-green-500/30 rounded-lg">
                                <p class="text-green-400 text-sm">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mt-4 p-3 bg-red-500/20 border border-red-500/30 rounded-lg">
                                <p class="text-red-400 text-sm">{{ session('error') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Kolom Kanan - Logo & Info (Hanya Desktop) -->
                <div
                    class="desktop-only w-full md:w-1/2 bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-8 md:p-12 flex flex-col items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div
                            class="absolute top-0 -left-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob">
                        </div>
                        <div
                            class="absolute bottom-0 -right-4 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000">
                        </div>
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="mb-8 relative group">
                            <div
                                class="absolute inset-0 bg-blue-400 rounded-full filter blur-3xl opacity-30 group-hover:opacity-50 transition-opacity duration-700 animate-pulse-slow">
                            </div>
                            <div class="relative animate-float">
                                <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo E-Ticketing"
                                    class="w-48 h-40 mx-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                                    onerror="this.style.display='none'">
                            </div>
                        </div>

                        <h3 class="text-3xl font-bold text-white mb-4">E-Ticketing System</h3>
                        <p class="text-gray-300 mb-6 max-w-md">
                            Digital HelpDesk & Incident Management
                        </p>
                        <p class="text-gray-300 mb-6 max-w-md">
                            IT BPR MODERN EXPRESS
                        </p>

                        <div
                            class="space-y-4 text-left bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-300">Ticketing Management</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-300">Real-time Tracking</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-gray-300">Detailed Reporting</span>
                            </div>
                        </div>

                        <p class="mt-8 text-sm text-gray-500">
                            © 2026 IT BPR MODERN EXPRESS. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function() {
            console.log('reCAPTCHA ready');
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {
                action: 'login'
            }).then(function(token) {
                console.log('reCAPTCHA token received');
                document.getElementById('recaptcha-token').value = token;
                e.target.submit();
            }).catch(function(error) {
                console.error('reCAPTCHA error:', error);
                alert('reCAPTCHA verification failed. Please refresh the page and try again.');
                submitBtn.disabled = false;
            });
        });

        document.querySelectorAll('input[type="password"], input[name="username"]').forEach(input => {
            input.addEventListener('contextmenu', e => e.preventDefault());
        });

        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('paste', e => e.preventDefault());
            passwordInput.addEventListener('copy', e => e.preventDefault());
            passwordInput.addEventListener('cut', e => e.preventDefault());
        }

        console.log('Alpine.js initialized:', typeof Alpine !== 'undefined');
    </script>
@endpush

@push('styles')
    <style>
        @keyframes blob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2000ms;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.5);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.8);
        }

        [x-cloak] {
            display: none !important;
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
@endpush
