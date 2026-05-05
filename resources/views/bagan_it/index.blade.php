@extends('layouts.app')

@section('title', 'Kenalan Bareng Kami - Struktur IT')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900">
        <!-- Konten halaman dengan scrollbar custom -->
        <div class="overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="text-center pt-6 pb-4 flex-shrink-0">
                <h1
                    class="text-2xl sm:text-3xl md:text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 mb-2">
                    Struktur Organisasi IT
                </h1>
                <p class="text-gray-300 text-sm sm:text-base">Kenali tim IT BPR MODERN EXPRESS</p>
                <div class="w-16 sm:w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto mt-2 rounded-full"></div>
            </div>

            <!-- Grid Kartu - Kiri ke Kanan -->
            <div class="max-w-7xl mx-auto px-4 pb-8">

                <!-- Section Leadership Team -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></span>
                        Leadership Team
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Kartu Kadiv IT -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('kadiv')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <!-- Foto Sampul / Cover Card -->
                                <div class="relative h-32 bg-gradient-to-r from-blue-600 to-purple-600">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            BS
                                        </div>
                                    </div>
                                    <!-- Badge -->
                                    <div
                                        class="absolute top-3 right-3 bg-blue-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Kadiv
                                    </div>
                                </div>

                                <!-- Konten Kartu -->
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Budi Santoso, S.Kom., M.M.</h3>
                                    <p class="text-blue-400 text-sm mb-3">Kepala Divisi IT</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">budi.santoso@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567890</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-700">
                                        <span class="text-xs text-gray-500">Klik untuk detail lengkap →</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Manager IT -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('manager')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-purple-600 to-pink-600">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            DL
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-purple-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Manager
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Dewi Lestari, S.T., M.Kom.</h3>
                                    <p class="text-purple-400 text-sm mb-3">Manager IT</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">dewi.lestari@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567891</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-700">
                                        <span class="text-xs text-gray-500">Klik untuk detail lengkap →</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section IT Digital -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-emerald-500 to-green-500 rounded-full"></span>
                        IT Digital
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Kartu Kasie Digital -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('kasie_digital')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-emerald-600 to-green-600">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            AW
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-emerald-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Koordinator
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Andi Wijaya, S.Kom.</h3>
                                    <p class="text-emerald-400 text-sm mb-3">Koordinator IT Digital</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">andi.wijaya@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567892</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Digital -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('digital_staff1')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-600 to-gray-700">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            JS
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-gray-600/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Staff
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Joko Susilo</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Digital</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">joko.susilo@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567805</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section IT Support (dengan grid yang lebih besar) -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="w-1 h-6 bg-gradient-to-b from-cyan-500 to-blue-500 rounded-full"></span>
                        IT Support
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <!-- Kartu Koordinator Support -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('kasie_support')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-cyan-600 to-blue-600">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            RF
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Koordinator
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Rina Fitriani, S.T.</h3>
                                    <p class="text-cyan-400 text-sm mb-3">Koordinator IT Support</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">rina.fitriani@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567893</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Area 1 - Staff 1 -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('support_area1_staff1')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-700 to-gray-800">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            AP
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Area 1
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Agus Pratama</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Support - Area 1</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">agus.pratama@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567896</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Area 1 - Staff 2 -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('support_area1_staff2')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-700 to-gray-800">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            BH
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Area 1
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Bambang Hermawan</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Support - Area 1</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">bambang.hermawan@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567897</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Area 2 -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('support_area2_staff1')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-700 to-gray-800">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            CD
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Area 2
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Citra Dewi</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Support - Area 2</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">citra.dewi@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567898</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Area 3 - Staff 1 -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('support_area3_staff1')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-700 to-gray-800">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            DS
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Area 3
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Doni Saputra</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Support - Area 3</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">doni.saputra@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567899</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Staff Area 3 - Staff 2 -->
                        <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                            onclick="showPersonDetail('support_area3_staff2')">
                            <div
                                class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                <div class="relative h-32 bg-gradient-to-r from-gray-700 to-gray-800">
                                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all"></div>
                                    <div class="absolute -bottom-12 left-4">
                                        <div
                                            class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                            EN
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 bg-cyan-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                        Area 3
                                    </div>
                                </div>
                                <div class="pt-14 p-4">
                                    <h3 class="font-bold text-white text-lg">Eka Nurcahyo</h3>
                                    <p class="text-gray-400 text-sm mb-3">Staff IT Support - Area 3</p>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">eka.nurcahyo@bprmodex.co.id</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            <span class="text-xs">081234567800</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section IT CBS & IT ATM dalam 1 baris -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- IT CBS -->
                    <div>
                        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-orange-500 to-red-500 rounded-full"></span>
                            IT CBS
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                                onclick="showPersonDetail('kasie_cbs')">
                                <div
                                    class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                    <div class="relative h-32 bg-gradient-to-r from-orange-600 to-red-600">
                                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all">
                                        </div>
                                        <div class="absolute -bottom-12 left-4">
                                            <div
                                                class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                                HG
                                            </div>
                                        </div>
                                        <div
                                            class="absolute top-3 right-3 bg-orange-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                            Koordinator
                                        </div>
                                    </div>
                                    <div class="pt-14 p-4">
                                        <h3 class="font-bold text-white text-lg">Hendra Gunawan, S.Kom.</h3>
                                        <p class="text-orange-400 text-sm mb-3">Koordinator IT CBS</p>
                                        <div class="flex items-center gap-2 text-gray-400 text-xs">
                                            <span>📧 hendra.gunawan@bprmodex.co.id</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-1"
                                    onclick="showPersonDetail('cbs_staff1')">
                                    <div
                                        class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700">
                                        <div class="relative h-24 bg-gradient-to-r from-gray-700 to-gray-800">
                                            <div class="absolute -bottom-8 left-3">
                                                <div
                                                    class="w-14 h-14 rounded-full border-2 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                                    FS
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-10 p-3">
                                            <h4 class="font-semibold text-white text-sm">Fajar Setiawan</h4>
                                            <p class="text-gray-400 text-xs">Staff CBS</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-1"
                                    onclick="showPersonDetail('cbs_staff2')">
                                    <div
                                        class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700">
                                        <div class="relative h-24 bg-gradient-to-r from-gray-700 to-gray-800">
                                            <div class="absolute -bottom-8 left-3">
                                                <div
                                                    class="w-14 h-14 rounded-full border-2 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                                    GM
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-10 p-3">
                                            <h4 class="font-semibold text-white text-sm">Gina Maharani</h4>
                                            <p class="text-gray-400 text-xs">Staff CBS</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- IT ATM -->
                    <div>
                        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-gradient-to-b from-rose-500 to-pink-500 rounded-full"></span>
                            IT ATM
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-2"
                                onclick="showPersonDetail('kasie_atm')">
                                <div
                                    class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-xl">
                                    <div class="relative h-32 bg-gradient-to-r from-rose-600 to-pink-600">
                                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/20 transition-all">
                                        </div>
                                        <div class="absolute -bottom-12 left-4">
                                            <div
                                                class="w-24 h-24 rounded-full border-4 border-gray-800 bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                                                SP
                                            </div>
                                        </div>
                                        <div
                                            class="absolute top-3 right-3 bg-rose-500/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-semibold text-white">
                                            Koordinator
                                        </div>
                                    </div>
                                    <div class="pt-14 p-4">
                                        <h3 class="font-bold text-white text-lg">Sari Puspita, S.T.</h3>
                                        <p class="text-rose-400 text-sm mb-3">Koordinator IT ATM</p>
                                        <div class="flex items-center gap-2 text-gray-400 text-xs">
                                            <span>📧 sari.puspita@bprmodex.co.id</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-1"
                                    onclick="showPersonDetail('atm_staff1')">
                                    <div
                                        class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700">
                                        <div class="relative h-24 bg-gradient-to-r from-gray-700 to-gray-800">
                                            <div class="absolute -bottom-8 left-3">
                                                <div
                                                    class="w-14 h-14 rounded-full border-2 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                                    HP
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-10 p-3">
                                            <h4 class="font-semibold text-white text-sm">Hadi Prayitno</h4>
                                            <p class="text-gray-400 text-xs">Staff ATM</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="group cursor-pointer transform transition-all duration-300 hover:-translate-y-1"
                                    onclick="showPersonDetail('atm_staff2')">
                                    <div
                                        class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden border border-gray-700">
                                        <div class="relative h-24 bg-gradient-to-r from-gray-700 to-gray-800">
                                            <div class="absolute -bottom-8 left-3">
                                                <div
                                                    class="w-14 h-14 rounded-full border-2 border-gray-800 bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                                    IP
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pt-10 p-3">
                                            <h4 class="font-semibold text-white text-sm">Indah Permata</h4>
                                            <p class="text-gray-400 text-xs">Staff ATM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Detail Person (sama seperti sebelumnya) -->
    <div id="personModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
        onclick="closeModal()">
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0"
            id="modalContent" onclick="event.stopPropagation()">
            <div class="relative">
                <button onclick="closeModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="w-24 h-24 mx-auto mb-3 rounded-full flex items-center justify-center" id="modalIcon">
                        </div>
                        <h3 class="text-2xl font-bold text-white" id="modalName">Nama Person</h3>
                        <p class="text-gray-300 mt-1" id="modalPosition">Posisi</p>
                    </div>
                    <div class="border-t border-gray-700 pt-4 space-y-3" id="modalDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data Person (sama seperti sebelumnya, sudah lengkap)
        const personsData = {
            kadiv: {
                name: "Budi Santoso, S.Kom., M.M.",
                position: "Kepala Divisi IT",
                icon: "bg-blue-500",
                initials: "BS",
                details: [{
                    label: "NIP",
                    value: "197501011998021001"
                }, {
                    label: "Email",
                    value: "budi.santoso@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567890"
                }, {
                    label: "Pendidikan",
                    value: "S2 Manajemen Sistem Informasi"
                }, {
                    label: "Bergabung",
                    value: "1998"
                }, {
                    label: "Keahlian",
                    value: "IT Strategy, Digital Transformation"
                }]
            },
            manager: {
                name: "Dewi Lestari, S.T., M.Kom.",
                position: "Manager IT",
                icon: "bg-purple-500",
                initials: "DL",
                details: [{
                    label: "NIP",
                    value: "198502152010012001"
                }, {
                    label: "Email",
                    value: "dewi.lestari@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567891"
                }, {
                    label: "Pendidikan",
                    value: "S2 Ilmu Komputer"
                }, {
                    label: "Bergabung",
                    value: "2010"
                }, {
                    label: "Keahlian",
                    value: "Project Management, IT Governance"
                }]
            },
            kasie_digital: {
                name: "Andi Wijaya, S.Kom.",
                position: "Koordinator IT Digital",
                icon: "bg-emerald-500",
                initials: "AW",
                details: [{
                    label: "NIP",
                    value: "198803152015031001"
                }, {
                    label: "Email",
                    value: "andi.wijaya@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567892"
                }, {
                    label: "Pendidikan",
                    value: "S1 Teknik Informatika"
                }, {
                    label: "Bergabung",
                    value: "2015"
                }, {
                    label: "Keahlian",
                    value: "Web Development, Mobile Apps"
                }]
            },
            kasie_support: {
                name: "Rina Fitriani, S.T.",
                position: "Koordinator IT Support",
                icon: "bg-cyan-500",
                initials: "RF",
                details: [{
                    label: "NIP",
                    value: "198912202012022001"
                }, {
                    label: "Email",
                    value: "rina.fitriani@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567893"
                }, {
                    label: "Pendidikan",
                    value: "S1 Sistem Informasi"
                }, {
                    label: "Bergabung",
                    value: "2012"
                }, {
                    label: "Keahlian",
                    value: "Hardware, Network, Troubleshooting"
                }]
            },
            kasie_cbs: {
                name: "Hendra Gunawan, S.Kom.",
                position: "Koordinator IT CBS",
                icon: "bg-orange-500",
                initials: "HG",
                details: [{
                    label: "NIP",
                    value: "199005102016031001"
                }, {
                    label: "Email",
                    value: "hendra.gunawan@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567894"
                }, {
                    label: "Pendidikan",
                    value: "S1 Teknik Informatika"
                }, {
                    label: "Bergabung",
                    value: "2016"
                }, {
                    label: "Keahlian",
                    value: "Core Banking System, Database"
                }]
            },
            kasie_atm: {
                name: "Sari Puspita, S.T.",
                position: "Koordinator IT ATM",
                icon: "bg-rose-500",
                initials: "SP",
                details: [{
                    label: "NIP",
                    value: "198703252011012001"
                }, {
                    label: "Email",
                    value: "sari.puspita@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567895"
                }, {
                    label: "Pendidikan",
                    value: "S1 Sistem Informasi"
                }, {
                    label: "Bergabung",
                    value: "2011"
                }, {
                    label: "Keahlian",
                    value: "ATM Management, Security System"
                }]
            },
            support_area1_staff1: {
                name: "Agus Pratama",
                position: "Staff IT Support Area 1",
                icon: "bg-gray-600",
                initials: "AP",
                details: [{
                    label: "NIP",
                    value: "199503202019101001"
                }, {
                    label: "Email",
                    value: "agus.pratama@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567896"
                }, {
                    label: "Area",
                    value: "Area 1 - Kantor Pusat"
                }, {
                    label: "Keahlian",
                    value: "Desktop Support, Networking"
                }]
            },
            support_area1_staff2: {
                name: "Bambang Hermawan",
                position: "Staff IT Support Area 1",
                icon: "bg-gray-600",
                initials: "BH",
                details: [{
                    label: "NIP",
                    value: "199208202018111002"
                }, {
                    label: "Email",
                    value: "bambang.hermawan@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567897"
                }, {
                    label: "Area",
                    value: "Area 1 - Kantor Pusat"
                }, {
                    label: "Keahlian",
                    value: "Hardware Maintenance, Printer"
                }]
            },
            support_area2_staff1: {
                name: "Citra Dewi",
                position: "Staff IT Support Area 2",
                icon: "bg-gray-600",
                initials: "CD",
                details: [{
                    label: "NIP",
                    value: "199610252021121001"
                }, {
                    label: "Email",
                    value: "citra.dewi@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567898"
                }, {
                    label: "Area",
                    value: "Area 2 - Cabang Timur"
                }, {
                    label: "Keahlian",
                    value: "Software Installation, User Support"
                }]
            },
            support_area3_staff1: {
                name: "Doni Saputra",
                position: "Staff IT Support Area 3",
                icon: "bg-gray-600",
                initials: "DS",
                details: [{
                    label: "NIP",
                    value: "199312012022011001"
                }, {
                    label: "Email",
                    value: "doni.saputra@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567899"
                }, {
                    label: "Area",
                    value: "Area 3 - Cabang Barat"
                }, {
                    label: "Keahlian",
                    value: "Network, CCTV"
                }]
            },
            support_area3_staff2: {
                name: "Eka Nurcahyo",
                position: "Staff IT Support Area 3",
                icon: "bg-gray-600",
                initials: "EN",
                details: [{
                    label: "NIP",
                    value: "199404152019091002"
                }, {
                    label: "Email",
                    value: "eka.nurcahyo@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567800"
                }, {
                    label: "Area",
                    value: "Area 3 - Cabang Barat"
                }, {
                    label: "Keahlian",
                    value: "Hardware, Troubleshooting"
                }]
            },
            cbs_staff1: {
                name: "Fajar Setiawan",
                position: "Staff IT CBS",
                icon: "bg-gray-600",
                initials: "FS",
                details: [{
                    label: "NIP",
                    value: "199601152020081001"
                }, {
                    label: "Email",
                    value: "fajar.setiawan@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567801"
                }, {
                    label: "Keahlian",
                    value: "Database Management, SQL"
                }]
            },
            cbs_staff2: {
                name: "Gina Maharani",
                position: "Staff IT CBS",
                icon: "bg-gray-600",
                initials: "GM",
                details: [{
                    label: "NIP",
                    value: "199712102021071002"
                }, {
                    label: "Email",
                    value: "gina.maharani@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567802"
                }, {
                    label: "Keahlian",
                    value: "System Analyst, Reporting"
                }]
            },
            atm_staff1: {
                name: "Hadi Prayitno",
                position: "Staff IT ATM",
                icon: "bg-gray-600",
                initials: "HP",
                details: [{
                    label: "NIP",
                    value: "199301212018061001"
                }, {
                    label: "Email",
                    value: "hadi.prayitno@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567803"
                }, {
                    label: "Keahlian",
                    value: "ATM Maintenance, Security"
                }]
            },
            atm_staff2: {
                name: "Indah Permata",
                position: "Staff IT ATM",
                icon: "bg-gray-600",
                initials: "IP",
                details: [{
                    label: "NIP",
                    value: "199408182019051002"
                }, {
                    label: "Email",
                    value: "indah.permata@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567804"
                }, {
                    label: "Keahlian",
                    value: "Monitoring, Troubleshooting ATM"
                }]
            },
            digital_staff1: {
                name: "Joko Susilo",
                position: "Staff IT Digital",
                icon: "bg-gray-600",
                initials: "JS",
                details: [{
                    label: "NIP",
                    value: "199511102022041001"
                }, {
                    label: "Email",
                    value: "joko.susilo@bprmodex.co.id"
                }, {
                    label: "Telepon",
                    value: "081234567805"
                }, {
                    label: "Keahlian",
                    value: "Web Developer, Mobile Developer"
                }]
            }
        };

        function showPersonDetail(personId) {
            const person = personsData[personId];
            if (!person) return;

            const modal = document.getElementById('personModal');
            const modalContent = document.getElementById('modalContent');

            document.getElementById('modalName').textContent = person.name;
            document.getElementById('modalPosition').textContent = person.position;

            const modalIcon = document.getElementById('modalIcon');
            modalIcon.className =
                `w-24 h-24 mx-auto mb-3 rounded-full flex items-center justify-center ${person.icon} text-white font-bold text-3xl`;
            modalIcon.innerHTML = person.initials;

            const detailsContainer = document.getElementById('modalDetails');
            detailsContainer.innerHTML = '';
            person.details.forEach(detail => {
                const detailDiv = document.createElement('div');
                detailDiv.className = 'flex justify-between items-center border-b border-gray-700/50 pb-2';
                detailDiv.innerHTML =
                    `<span class="text-gray-400 text-sm">${detail.label}</span><span class="text-white text-sm font-medium">${detail.value}</span>`;
                detailsContainer.appendChild(detailDiv);
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('personModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>

    @push('styles')
        <style>
            .overflow-y-auto::-webkit-scrollbar {
                width: 5px;
            }

            .overflow-y-auto::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.03);
                border-radius: 10px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: rgba(59, 130, 246, 0.3);
                border-radius: 10px;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: rgba(59, 130, 246, 0.6);
            }

            .hidden {
                display: none !important;
            }
        </style>
    @endpush
@endsection
