@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="min-h-screen bg-custom-dark flex items-center justify-center p-4">
        <div class="max-w-2xl w-full text-center">
            <!-- Animated 404 -->
            <div class="relative mb-8">
                <h1 class="text-9xl font-bold text-white/10">404</h1>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative">
                        <div class="w-32 h-32 bg-blue-500/20 rounded-full animate-ping absolute"></div>
                        <div class="w-24 h-24 bg-blue-500/30 rounded-full animate-pulse absolute left-4 top-4"></div>
                        <svg class="w-32 h-32 text-blue-400 relative z-10" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-white mb-4">Page Not Found</h2>
            <p class="text-gray-400 mb-8">The page you are looking for might have been removed, had its name changed, or is
                temporarily unavailable.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Back to Home
                </a>
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-semibold py-3 px-6 rounded-xl border border-white/10 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Go to Login
                </a>
            </div>

            <!-- Security Note -->
            <div class="mt-12 p-4 bg-white/5 rounded-lg border border-white/10 inline-block">
                <p class="text-sm text-gray-400">This incident has been logged. If you believe this is an error, please
                    contact IT support.</p>
            </div>
        </div>
    </div>
@endsection
