@extends('layouts.app')

@section('title', 'E-Ticketing System')

@section('content')
    <div class="relative bg-custom-dark min-h-screen overflow-y-auto scroll-smooth" style="height: 100vh;">
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

            /* Smooth scroll behavior */
            html {
                scroll-behavior: smooth;
            }
        </style>

        <!-- Background Futuristik dengan Grid dan Gradient -->
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(0,123,255,0.15),transparent_50%),radial-gradient(ellipse_at_bottom,rgba(0,255,209,0.1),transparent_50%)]">
        </div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60"
            viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg
            fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath
            d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"
            /%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); opacity: 0.2;"></div>

        <!-- Animated Gradient Orbs -->
        <div
            class="absolute top-20 -left-20 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-40 -right-20 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute bottom-20 left-1/2 w-80 h-80 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
        </div>

        <!-- Navbar dengan efek glassmorphism (Sticky) -->
        <nav
            class="sticky top-0 z-50 relative flex items-center justify-between px-6 py-3 md:py-2 md:px-8 backdrop-blur-md bg-white/5 border-b border-white/10 shadow-lg">
            <!-- Logo di kiri dengan efek glow -->
            <div class="flex items-center group">
                <div class="relative">
                    <div
                        class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-0 group-hover:opacity-70 transition-opacity duration-500">
                    </div>
                    <img src="{{ asset('assets/logo1.PNG') }}" alt="Logo"
                        class="relative h-13 w-auto mr-3 brightness-0 invert transform group-hover:scale-110 transition-transform duration-300"
                        onerror="this.style.display='none'">
                </div>
            </div>

            <!-- Tulisan DEVELOP BY IT DIGITAL dengan efek futuristik -->
            <div class="relative">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full filter blur-md opacity-0 hover:opacity-50 transition-opacity duration-300">
                </div>
                <span
                    class="relative text-sm font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200 tracking-wider hover:from-blue-300 hover:to-purple-300 transition-all duration-300 cursor-default">
                    ✦ DEVELOP BY IT DIGITAL ✦
                </span>
            </div>
        </nav>

        <!-- Hero Section dengan efek futuristik -->
        <div class="relative flex flex-col items-center justify-center text-center px-4 mt-16 md:mt-30 z-10 pb-20">
            <!-- Logo Utama dengan efek 3D dan glow -->
            <div class="relative mb-8 group">
                <!-- Multiple shadow layers untuk efek depth -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full filter blur-3xl opacity-30 group-hover:opacity-60 transition-opacity duration-700 animate-pulse-slow">
                </div>
                <div
                    class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-700">
                </div>

                <!-- Logo dengan efek floating -->
                <div class="relative animate-float">
                    <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo E-Ticketing"
                        class="h-40 w-auto mx-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                        onerror="this.style.display='none'">
                </div>

                <!-- Lingkaran dekoratif -->
                <div class="absolute -top-4 -left-4 w-20 h-20 border-2 border-blue-400/30 rounded-full animate-spin-slow">
                </div>
                <div
                    class="absolute -bottom-4 -right-4 w-16 h-16 border-2 border-purple-400/30 rounded-full animate-spin-slow animation-delay-1000">
                </div>
            </div>

            <!-- Title dengan efek glitch dan gradient -->
            <div class="relative mb-4">
                <h1 class="text-6xl md:text-7xl font-black mb-2 relative">
                    <span class="absolute inset-0 text-blue-400 animate-glitch-1 opacity-70">E-Ticketing System</span>
                    <span class="absolute inset-0 text-purple-400 animate-glitch-2 opacity-70">E-Ticketing System</span>
                    <span
                        class="relative text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-white to-purple-300 animate-gradient-x">
                        E-Ticketing System
                    </span>
                </h1>
                <!-- Garis dekoratif -->
                <div
                    class="w-32 h-1 mx-auto bg-gradient-to-r from-transparent via-blue-400 to-transparent rounded-full mt-4">
                </div>
            </div>

            <!-- Subtitle dengan efek typing -->
            <p class="text-xl md:text-2xl text-gray-300 mb-3 max-w-2xl font-light tracking-wide animate-fade-in-up">
                Digital HelpDesk & Incident Management
            </p>

            <!-- Company name dengan efek futuristik -->
            <div class="relative mb-12">
                <span class="text-lg text-gray-400 relative px-6 py-2">
                    <span
                        class="absolute left-0 top-1/2 w-3 h-3 bg-blue-400 rounded-full transform -translate-y-1/2 animate-ping opacity-50"></span>
                    IT BPR MODERN EXPRESS
                    <span
                        class="absolute right-0 top-1/2 w-3 h-3 bg-purple-400 rounded-full transform -translate-y-1/2 animate-ping opacity-50 animation-delay-500"></span>
                </span>
            </div>

            <!-- 3 Tombol dengan efek futuristik -->
            <div class="flex flex-col sm:flex-row gap-5 justify-center mb-16">
                <!-- Tombol Login dengan efek cyber -->
                <a href="{{ route('login') }}"
                    class="group relative px-8 py-4 min-w-[160px] text-center overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Login
                    </span>
                    <div
                        class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%)] translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                    </div>
                </a>

                <!-- Tombol Lapor Masalah dengan efek cyber -->
                <a href="{{ route('laporan.create') }}"
                    class="group relative px-8 py-4 min-w-[160px] text-center overflow-hidden rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Lapor Masalah
                    </span>
                    <div
                        class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%)] translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                    </div>
                </a>

                <!-- Tombol Detail Laporan dengan efek cyber -->
                <a href="{{ route('detail_laporan.index') }}"
                    class="group relative px-8 py-4 min-w-[160px] text-center overflow-hidden rounded-2xl bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/50">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Detail Laporan
                    </span>
                    <div
                        class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%)] translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-1000">
                    </div>
                </a>
            </div>

            <!-- Efek partikel tambahan -->
            <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent">
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Animations */
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

        @keyframes glitch-1 {

            0%,
            100% {
                transform: translate(0);
            }

            20% {
                transform: translate(-2px, 2px);
            }

            40% {
                transform: translate(-2px, -2px);
            }

            60% {
                transform: translate(2px, 2px);
            }

            80% {
                transform: translate(2px, -2px);
            }
        }

        @keyframes glitch-2 {

            0%,
            100% {
                transform: translate(0);
            }

            20% {
                transform: translate(2px, -2px);
            }

            40% {
                transform: translate(2px, 2px);
            }

            60% {
                transform: translate(-2px, -2px);
            }

            80% {
                transform: translate(-2px, 2px);
            }
        }

        @keyframes gradient-x {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
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

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-glitch-1 {
            animation: glitch-1 3s infinite;
        }

        .animate-glitch-2 {
            animation: glitch-2 2.5s infinite;
        }

        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 5s ease infinite;
        }

        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 4s ease-in-out infinite;
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }

        .animation-delay-500 {
            animation-delay: 500ms;
        }

        .animation-delay-1000 {
            animation-delay: 1000ms;
        }

        .animation-delay-2000 {
            animation-delay: 2000ms;
        }

        .animation-delay-4000 {
            animation-delay: 4000ms;
        }
    </style>
@endpush
