@extends('layouts.app')

@section('title', 'E-Ticketing System')

@section('content')
    <div class="relative bg-custom-dark w-screen h-screen overflow-hidden">
        <!-- Custom Styling untuk fixed layout -->
        <style>
            /* Reset untuk memastikan tidak ada scroll */
            body {
                overflow: hidden !important;
            }

            /* Custom Scrollbar - hanya untuk konten yang memang perlu scroll (hidden) */
            .custom-scroll {
                overflow-y: auto;
            }

            .custom-scroll::-webkit-scrollbar {
                width: 5px;
            }

            .custom-scroll::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.03);
                border-radius: 10px;
            }

            .custom-scroll::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.5), rgba(139, 92, 246, 0.5));
                border-radius: 10px;
            }

            .custom-scroll::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 92, 246, 0.8));
            }

            /* Fixed positioning untuk semua device */
            .fixed-layout {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100vw;
                height: 100vh;
                overflow: hidden;
            }

            /* Responsive font sizes */
            @media (max-width: 640px) {
                .hero-title {
                    font-size: 2rem !important;
                    line-height: 1.2 !important;
                }

                .hero-subtitle {
                    font-size: 1rem !important;
                }

                .hero-logo {
                    height: 100px !important;
                }

                .hero-buttons {
                    flex-direction: column !important;
                    gap: 0.75rem !important;
                }

                .hero-buttons a {
                    width: 100% !important;
                    min-width: 200px !important;
                }
            }

            @media (min-width: 641px) and (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem !important;
                }

                .hero-logo {
                    height: 120px !important;
                }
            }

            @media (min-width: 769px) and (max-width: 1024px) {
                .hero-title {
                    font-size: 3.5rem !important;
                }

                .hero-logo {
                    height: 140px !important;
                }
            }

            @media (min-width: 1025px) {
                .hero-title {
                    font-size: 4rem !important;
                }

                .hero-logo {
                    height: 160px !important;
                }
            }

            /* Animations */
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

        <!-- Fixed Layout Container -->
        <div class="fixed-layout">
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

            <!-- Navbar dengan efek glassmorphism (Sticky) - Fully Responsive -->
            <nav
                class="relative z-50 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0 px-4 sm:px-6 md:px-8 py-3 md:py-2 backdrop-blur-md bg-white/5 border-b border-white/10 shadow-lg">
                <!-- Logo di kiri dengan efek glow -->
                <div class="flex items-center group">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-0 group-hover:opacity-70 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('assets/logo1.PNG') }}" alt="Logo"
                            class="relative h-9 sm:h-10 md:h-11 lg:h-12 w-auto mr-2 sm:mr-3 brightness-0 invert transform group-hover:scale-110 transition-transform duration-300"
                            onerror="this.style.display='none'">
                    </div>
                </div>

                <!-- Tulisan DEVELOP BY IT DIGITAL dengan efek futuristik (Responsive) -->
                <div class="relative w-full sm:w-auto text-center">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full filter blur-md opacity-0 hover:opacity-50 transition-opacity duration-300">
                    </div>
                    <span
                        class="relative inline-block text-[10px] sm:text-xs md:text-sm font-medium text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200 tracking-wider hover:from-blue-300 hover:to-purple-300 transition-all duration-300 cursor-default px-2 sm:px-0">
                        <span class="hidden xs:inline">✦</span> DEVELOP BY IT DIGITAL <span
                            class="hidden xs:inline">✦</span>
                    </span>
                </div>
            </nav>

            <!-- Hero Section - Flex Column dengan height penuh agar tidak scroll -->
            <div class="relative flex flex-col items-center justify-center text-center px-4 z-10"
                style="height: calc(100vh - 60px);">
                <!-- Logo Utama dengan efek 3D dan glow -->
                <div class="relative mb-6 sm:mb-8 group">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full filter blur-3xl opacity-30 group-hover:opacity-60 transition-opacity duration-700 animate-pulse-slow">
                    </div>
                    <div
                        class="absolute inset-0 bg-blue-400 rounded-full filter blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-700">
                    </div>

                    <div class="relative animate-float">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo E-Ticketing"
                            class="hero-logo h-24 sm:h-28 md:h-32 lg:h-36 xl:h-40 w-auto mx-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                            onerror="this.style.display='none'">
                    </div>

                    <div
                        class="absolute -top-4 -left-4 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 border-2 border-blue-400/30 rounded-full animate-spin-slow">
                    </div>
                    <div
                        class="absolute -bottom-4 -right-4 w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 border-2 border-purple-400/30 rounded-full animate-spin-slow animation-delay-1000">
                    </div>
                </div>

                <!-- Title dengan efek glitch dan gradient -->
                <div class="relative mb-3 sm:mb-4">
                    <h1
                        class="hero-title text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black mb-2 relative">
                        <span class="absolute inset-0 text-blue-400 animate-glitch-1 opacity-70">E-Ticketing System</span>
                        <span class="absolute inset-0 text-purple-400 animate-glitch-2 opacity-70">E-Ticketing System</span>
                        <span
                            class="relative text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-white to-purple-300 animate-gradient-x">
                            E-Ticketing System
                        </span>
                    </h1>
                    <div
                        class="w-20 sm:w-24 md:w-28 lg:w-32 h-1 mx-auto bg-gradient-to-r from-transparent via-blue-400 to-transparent rounded-full mt-3 sm:mt-4">
                    </div>
                </div>

                <!-- Subtitle dengan efek typing -->
                <p
                    class="hero-subtitle text-base sm:text-lg md:text-xl lg:text-2xl text-gray-300 mb-2 sm:mb-3 max-w-2xl font-light tracking-wide animate-fade-in-up px-4">
                    Digital HelpDesk & Incident Management
                </p>

                <!-- Company name dengan efek futuristik -->
                <div class="relative mb-8 sm:mb-10 md:mb-12">
                    <span class="text-sm sm:text-base md:text-lg text-gray-400 relative px-4 sm:px-6 py-2">
                        <span
                            class="absolute left-0 top-1/2 w-2 h-2 sm:w-3 sm:h-3 bg-blue-400 rounded-full transform -translate-y-1/2 animate-ping opacity-50"></span>
                        IT BPR MODERN EXPRESS
                        <span
                            class="absolute right-0 top-1/2 w-2 h-2 sm:w-3 sm:h-3 bg-purple-400 rounded-full transform -translate-y-1/2 animate-ping opacity-50 animation-delay-500"></span>
                    </span>
                </div>

                <!-- 3 Tombol dengan efek futuristik -->
                <div
                    class="hero-buttons flex flex-col sm:flex-row gap-3 sm:gap-4 md:gap-5 justify-center mb-8 sm:mb-10 w-full sm:w-auto px-4 sm:px-0">
                    <!-- Tombol Login -->
                    <a href="{{ route('login') }}"
                        class="group relative px-5 sm:px-6 md:px-8 py-3 sm:py-4 min-w-[140px] sm:min-w-[160px] text-center overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50">
                        <span class="relative z-10 flex items-center justify-center gap-2 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Tombol Lapor Masalah -->
                    <a href="{{ route('laporan.create') }}"
                        class="group relative px-5 sm:px-6 md:px-8 py-3 sm:py-4 min-w-[140px] sm:min-w-[160px] text-center overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50">
                        <span class="relative z-10 flex items-center justify-center gap-2 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Tombol Detail Laporan -->
                    <a href="{{ route('detail_laporan.index') }}"
                        class="group relative px-5 sm:px-6 md:px-8 py-3 sm:py-4 min-w-[140px] sm:min-w-[160px] text-center overflow-hidden rounded-xl sm:rounded-2xl bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/50">
                        <span class="relative z-10 flex items-center justify-center gap-2 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div
                    class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent">
                </div>
            </div>
        </div>
    </div>
@endsection
