@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
    <div class="min-h-screen bg-custom-dark flex items-center justify-center p-4">
        <div class="max-w-2xl w-full text-center">
            <!-- Animated 500 -->
            <div class="relative mb-8">
                <h1 class="text-9xl font-bold text-white/10">500</h1>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative">
                        <div class="w-32 h-32 bg-red-500/20 rounded-full animate-ping absolute"></div>
                        <div class="w-24 h-24 bg-red-500/30 rounded-full animate-pulse absolute left-4 top-4"></div>
                        <svg class="w-32 h-32 text-red-400 relative z-10" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-white mb-4">Internal Server Error</h2>
            <p class="text-gray-400 mb-8">Something went wrong on our servers. We're working to fix the issue as soon as
                possible.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()"
                    class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Try Again
                </button>
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-semibold py-3 px-6 rounded-xl border border-white/10 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Back to Home
                </a>
            </div>

            <!-- Error Reference -->
            <div class="mt-12 p-4 bg-white/5 rounded-lg border border-white/10 inline-block">
                <p class="text-sm text-gray-400">Error Reference: #ERR-{{ date('Ymd') }}-{{ substr(uniqid(), -4) }}</p>
                <p class="text-xs text-gray-500 mt-1">Our technical team has been notified. Please try again later.</p>
            </div>
        </div>
    </div>
@endsection
