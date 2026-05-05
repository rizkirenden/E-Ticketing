@extends('layouts.app')

@section('title', 'Dashboard - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <div class="flex-1 p-3 sm:p-4 md:p-6 overflow-y-auto" style="height: 100vh;">
            <!-- Header dengan Filter -->
            <div class="mb-4 sm:mb-6 md:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white mb-1 sm:mb-2">Dashboard</h1>
                    <p class="text-xs sm:text-sm text-gray-400">Welcome back! Here's your ticket statistics</p>
                </div>

                <!-- Filter Tahun -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <select id="tahunFilter"
                        class="bg-white/5 border border-white/10 text-gray-300 text-xs sm:text-sm rounded-lg px-3 sm:px-4 py-1.5 sm:py-2.5 focus:border-blue-400 focus:outline-none">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    <button id="refreshData"
                        class="p-1.5 sm:p-2.5 bg-blue-500/20 rounded-lg hover:bg-blue-500/30 transition-colors">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- 6 Card Status Individual - Responsif -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3 md:gap-4 mb-4 sm:mb-6 md:mb-8">
                <!-- Open -->
                <div
                    class="bg-gradient-to-br from-yellow-600/20 to-yellow-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-yellow-400 text-[9px] sm:text-xs font-semibold">OPEN</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">Open Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="openValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['open']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Process -->
                <div
                    class="bg-gradient-to-br from-blue-600/20 to-blue-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <span class="text-blue-400 text-[9px] sm:text-xs font-semibold">PROCESS</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">In Progress</h3>
                    <div class="flex items-end justify-between">
                        <span id="processValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['process']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Pending -->
                <div
                    class="bg-gradient-to-br from-orange-600/20 to-orange-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-orange-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-orange-400 text-[9px] sm:text-xs font-semibold">PENDING</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">Pending Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="pendingValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['pending']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Escalate -->
                <div
                    class="bg-gradient-to-br from-purple-600/20 to-purple-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-purple-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span class="text-purple-400 text-[9px] sm:text-xs font-semibold">ESCALATE</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">Escalated Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="escalateValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['escalate']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Done -->
                <div
                    class="bg-gradient-to-br from-green-600/20 to-green-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-green-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-green-400 text-[9px] sm:text-xs font-semibold">DONE</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">Done Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="doneValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['done']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Reject -->
                <div
                    class="bg-gradient-to-br from-red-600/20 to-red-600/10 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-2 sm:p-3 md:p-4">
                    <div class="flex items-center justify-between mb-1 sm:mb-2">
                        <div
                            class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-red-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-red-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="text-red-400 text-[9px] sm:text-xs font-semibold">REJECT</span>
                    </div>
                    <h3 class="text-gray-400 text-[9px] sm:text-xs mb-0.5 sm:mb-1">Rejected Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="rejectValue"
                            class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ number_format($statusCount['reject']) }}</span>
                        <span class="text-gray-500 text-[8px] sm:text-xs">{{ $tahun }}</span>
                    </div>
                </div>
            </div>

            <!-- 3 Card Ringkasan - Responsif -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Total Tickets -->
                <div
                    class="bg-gradient-to-br from-blue-600/20 to-purple-600/20 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-blue-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-xs sm:text-sm mb-0.5 sm:mb-1">Total Tickets</h3>
                    <div class="flex items-end justify-between">
                        <span id="totalTicketsValue"
                            class="text-xl sm:text-2xl md:text-3xl font-bold text-white">{{ number_format($totalTickets) }}</span>
                        <span class="text-gray-500 text-xs sm:text-sm">{{ $tahun }}</span>
                    </div>
                </div>

                <!-- Active Tickets -->
                <div
                    class="bg-gradient-to-br from-orange-600/20 to-red-600/20 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-orange-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-orange-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-xs sm:text-sm mb-0.5 sm:mb-1">Active Tickets</h3>
                    <div class="flex flex-col sm:block">
                        <div class="flex items-end justify-between">
                            <span id="activeTicketsValue"
                                class="text-xl sm:text-2xl md:text-3xl font-bold text-white">{{ $activeTickets }}</span>
                            <span id="activePercentage" class="text-gray-500 text-xs sm:text-sm">{{ $activePercentage }}%
                                of total</span>
                        </div>
                    </div>
                    <div class="mt-1 sm:mt-2 text-[9px] sm:text-xs text-gray-500 flex flex-wrap gap-1 sm:gap-2">
                        Open: <span id="openDetail">{{ $statusCount['open'] }}</span> |
                        Process: <span id="processDetail">{{ $statusCount['process'] }}</span> |
                        Pending: <span id="pendingDetail">{{ $statusCount['pending'] }}</span> |
                        Escalate: <span id="escalateDetail">{{ $statusCount['escalate'] }}</span>
                    </div>
                </div>

                <!-- Closed Tickets -->
                <div
                    class="bg-gradient-to-br from-green-600/20 to-emerald-600/20 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-2 sm:mb-3 md:mb-4">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-green-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-400 text-xs sm:text-sm mb-0.5 sm:mb-1">Closed Tickets</h3>
                    <div class="flex flex-col sm:block">
                        <div class="flex items-end justify-between">
                            <span id="closedTicketsValue"
                                class="text-xl sm:text-2xl md:text-3xl font-bold text-white">{{ number_format($closedTickets) }}</span>
                            <span id="closedPercentage" class="text-gray-500 text-xs sm:text-sm">{{ $closedPercentage }}%
                                of total</span>
                        </div>
                    </div>
                    <div class="mt-1 sm:mt-2 text-[9px] sm:text-xs text-gray-500">
                        Done: <span id="doneDetail">{{ $totalDone }}</span> ({{ $donePercentage }}%) |
                        Reject: <span id="rejectDetail">{{ $totalReject }}</span> ({{ $rejectPercentage }}%)
                    </div>
                </div>
            </div>

            <!-- 2 Chart Utama - Responsif -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Chart 1: Tren Tiket per Bulan -->
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4 sm:mb-6">
                        <h3 class="text-white text-sm sm:text-base font-semibold">Tiket per Bulan - <span
                                id="tahunDisplay">{{ $tahun }}</span></h3>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 text-xs sm:text-sm">Total: <span id="totalTahunIni"
                                    class="text-white font-bold">{{ $totalTahunIni }}</span></span>
                        </div>
                    </div>
                    <div class="h-48 sm:h-56 md:h-64">
                        <canvas id="trenTiketChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Distribusi per Aplikasi -->
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-white text-sm sm:text-base font-semibold">Distribusi per Aplikasi - <span
                                id="tahunDisplay2">{{ $tahun }}</span></h3>
                    </div>
                    <div class="h-48 sm:h-56 md:h-64">
                        <canvas id="distribusiAplikasiChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Statistik Tambahan - Responsif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 md:gap-6 mb-4 sm:mb-6 md:mb-8">
                <!-- Top Kantor -->
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <h3 class="text-white text-sm sm:text-base font-semibold mb-3 sm:mb-4">Top Kantor - <span
                            id="tahunDisplay3">{{ $tahun }}</span></h3>
                    <div id="topKantorContainer" class="space-y-2 sm:space-y-3">
                        @foreach ($statistikKantor->sortByDesc('laporans_count')->take(5) as $kantor)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div
                                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <span class="text-blue-400 text-xs sm:text-sm">{{ $loop->iteration }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-white text-xs sm:text-sm truncate">{{ $kantor->nama_cabang }}</p>
                                        <p class="text-gray-500 text-[10px] sm:text-xs">{{ $kantor->area }}</p>
                                    </div>
                                </div>
                                <span
                                    class="text-white font-bold text-sm sm:text-base ml-2">{{ $kantor->laporans_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status Distribution Detail -->
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <h3 class="text-white text-sm sm:text-base font-semibold mb-3 sm:mb-4">Status Tiket - <span
                            id="tahunDisplay4">{{ $tahun }}</span></h3>
                    <div id="statusContainer" class="space-y-2 sm:space-y-3">
                        @php
                            $statusColors = [
                                'open' => 'yellow',
                                'process' => 'blue',
                                'pending' => 'orange',
                                'escalate' => 'purple',
                                'done' => 'green',
                                'reject' => 'red',
                            ];
                            $statusLabels = [
                                'open' => 'Open',
                                'process' => 'In Progress',
                                'pending' => 'Pending',
                                'escalate' => 'Escalated',
                                'done' => 'Done',
                                'reject' => 'Rejected',
                            ];
                        @endphp
                        @foreach ($statusCount as $status => $count)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-{{ $statusColors[$status] ?? 'gray' }}-400 mr-2">
                                    </div>
                                    <span
                                        class="text-gray-400 text-xs sm:text-sm">{{ $statusLabels[$status] ?? ucfirst($status) }}</span>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <span class="text-white font-bold text-xs sm:text-sm">{{ $count }}</span>
                                    <span
                                        class="text-gray-500 text-[10px] sm:text-xs">{{ round(($count / max($totalTickets, 1)) * 100) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Users Overview -->
                <div
                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                    <h3 class="text-white text-sm sm:text-base font-semibold mb-3 sm:mb-4">Users Overview</h3>
                    <div class="text-center mb-3 sm:mb-4">
                        <span class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">{{ $totalUsers }}</span>
                        <span class="text-gray-400 text-xs sm:text-sm block">Total Users</span>
                    </div>
                    <div class="space-y-2 sm:space-y-2.5">
                        @foreach ($usersPerRole as $role)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs sm:text-sm">{{ $role->nama_role }}</span>
                                <span class="text-white font-bold text-sm sm:text-base">{{ $role->users_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Tickets Table - Responsif -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4 sm:mb-6">
                    <h3 class="text-white text-sm sm:text-base font-semibold">Recent Tickets - <span
                            id="tahunDisplay5">{{ $tahun }}</span></h3>
                    <a href="{{ route('laporan.index') }}"
                        class="text-blue-400 text-xs sm:text-sm hover:text-blue-300 transition-colors text-center sm:text-left">
                        View All →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] sm:min-w-full">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1">
                                    Ticket #</th>
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1">
                                    Pelapor</th>
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1 hidden sm:table-cell">
                                    Kantor</th>
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1 hidden md:table-cell">
                                    Aplikasi</th>
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1">
                                    Status</th>
                                <th
                                    class="text-left text-gray-400 text-[10px] sm:text-xs font-medium pb-2 sm:pb-3 px-2 sm:px-1 hidden lg:table-cell">
                                    Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="recentTicketsBody">
                            @foreach ($recentTickets as $ticket)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-2 sm:py-3 text-white text-[10px] sm:text-xs px-2 sm:px-1 font-mono">
                                        {{ $ticket->nomor_ticket }}</td>
                                    <td class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1">
                                        {{ $ticket->nama_pelapor }}</td>
                                    <td
                                        class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1 hidden sm:table-cell">
                                        {{ $ticket->kantor->nama_cabang ?? '-' }}</td>
                                    <td
                                        class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1 hidden md:table-cell">
                                        {{ $ticket->jenisAplikasi->jenis_aplikasi ?? '-' }}</td>
                                    <td class="py-2 sm:py-3 px-2 sm:px-1">
                                        <span
                                            class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-[8px] sm:text-[10px]
                                            @if ($ticket->status == 'done') bg-green-400/10 text-green-400
                                            @elseif($ticket->status == 'open') bg-yellow-400/10 text-yellow-400
                                            @elseif($ticket->status == 'process') bg-blue-400/10 text-blue-400
                                            @elseif($ticket->status == 'pending') bg-orange-400/10 text-orange-400
                                            @elseif($ticket->status == 'escalate') bg-purple-400/10 text-purple-400
                                            @elseif($ticket->status == 'reject') bg-red-400/10 text-red-400
                                            @else bg-gray-400/10 text-gray-400 @endif">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td
                                        class="py-2 sm:py-3 text-gray-300 text-[9px] sm:text-xs px-2 sm:px-1 hidden lg:table-cell">
                                        {{ $ticket->tanggal_laporan->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
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

        canvas {
            max-width: 100%;
            height: auto !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxTren = document.getElementById('trenTiketChart').getContext('2d');
            const ctxDistribusi = document.getElementById('distribusiAplikasiChart').getContext('2d');

            let trenChart, distribusiChart;

            const initialBarData = @json(array_values($dataPerBulan));
            const initialPieData = @json($pieData);

            function updateMainCards(data) {
                document.getElementById('totalTicketsValue').textContent = data.totalTickets.toLocaleString();
                document.getElementById('activeTicketsValue').textContent = data.activeTickets;
                document.getElementById('activePercentage').textContent = data.activePercentage + '% of total';
                document.getElementById('openDetail').textContent = data.statusData.open;
                document.getElementById('processDetail').textContent = data.statusData.process;
                document.getElementById('pendingDetail').textContent = data.statusData.pending;
                document.getElementById('escalateDetail').textContent = data.statusData.escalate;
                document.getElementById('closedTicketsValue').textContent = data.closedTickets.toLocaleString();
                document.getElementById('closedPercentage').textContent = data.closedPercentage + '% of total';
                document.getElementById('doneDetail').innerHTML = data.statusData.done + ' (' + data
                    .donePercentage + '%)';
                document.getElementById('rejectDetail').innerHTML = data.statusData.reject + ' (' + data
                    .rejectPercentage + '%)';
                document.getElementById('openValue').textContent = data.statusData.open.toLocaleString();
                document.getElementById('processValue').textContent = data.statusData.process.toLocaleString();
                document.getElementById('pendingValue').textContent = data.statusData.pending.toLocaleString();
                document.getElementById('escalateValue').textContent = data.statusData.escalate.toLocaleString();
                document.getElementById('doneValue').textContent = data.statusData.done.toLocaleString();
                document.getElementById('rejectValue').textContent = data.statusData.reject.toLocaleString();
            }

            function updateStatusTable(statusData) {
                const statusMapping = {
                    open: {
                        label: 'Open',
                        color: 'yellow'
                    },
                    process: {
                        label: 'In Progress',
                        color: 'blue'
                    },
                    pending: {
                        label: 'Pending',
                        color: 'orange'
                    },
                    escalate: {
                        label: 'Escalated',
                        color: 'purple'
                    },
                    done: {
                        label: 'Done',
                        color: 'green'
                    },
                    reject: {
                        label: 'Rejected',
                        color: 'red'
                    }
                };
                const totalTickets = Object.values(statusData).reduce((a, b) => a + b, 0);
                const statusContainer = document.getElementById('statusContainer');
                if (statusContainer) {
                    let newHtml = '';
                    for (const [status, count] of Object.entries(statusData)) {
                        const config = statusMapping[status] || {
                            label: status,
                            color: 'gray'
                        };
                        const percentage = totalTickets > 0 ? Math.round((count / totalTickets) * 100) : 0;
                        newHtml += `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-${config.color}-400 mr-2"></div>
                                    <span class="text-gray-400 text-xs sm:text-sm">${config.label}</span>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <span class="text-white font-bold text-xs sm:text-sm">${count}</span>
                                    <span class="text-gray-500 text-[10px] sm:text-xs">${percentage}%</span>
                                </div>
                            </div>
                        `;
                    }
                    statusContainer.innerHTML = newHtml;
                }
            }

            function updateRecentTickets(tickets) {
                const tableBody = document.getElementById('recentTicketsBody');
                if (!tableBody) return;
                let newRows = '';
                tickets.forEach(ticket => {
                    newRows += `
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-2 sm:py-3 text-white text-[10px] sm:text-xs px-2 sm:px-1 font-mono">${ticket.nomor_ticket}</td>
                            <td class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1">${ticket.nama_pelapor}</td>
                            <td class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1 hidden sm:table-cell">${ticket.kantor}</td>
                            <td class="py-2 sm:py-3 text-gray-300 text-[10px] sm:text-xs px-2 sm:px-1 hidden md:table-cell">${ticket.aplikasi}</td>
                            <td class="py-2 sm:py-3 px-2 sm:px-1"><span class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full text-[8px] sm:text-[10px] ${ticket.status_badge_class}">${ticket.status.charAt(0).toUpperCase() + ticket.status.slice(1)}</span></td>
                            <td class="py-2 sm:py-3 text-gray-300 text-[9px] sm:text-xs px-2 sm:px-1 hidden lg:table-cell">${ticket.tanggal}</td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = newRows;
            }

            function updateYearDisplays(tahun) {
                const displays = document.querySelectorAll('[id^="tahunDisplay"]');
                displays.forEach(display => {
                    if (display) display.textContent = tahun;
                });
            }

            function initCharts(barData, pieData) {
                if (trenChart) trenChart.destroy();
                if (distribusiChart) distribusiChart.destroy();

                trenChart = new Chart(ctxTren, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ],
                        datasets: [{
                            label: 'Jumlah Tiket',
                            data: barData,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#9CA3AF',
                                borderColor: '#3B82F6',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#9CA3AF',
                                    stepSize: 1,
                                    callback: (value) => Number.isInteger(value) ? value : null
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9CA3AF',
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });

                if (pieData.length > 0) {
                    distribusiChart = new Chart(ctxDistribusi, {
                        type: 'doughnut',
                        data: {
                            labels: pieData.map(item => item.name),
                            datasets: [{
                                data: pieData.map(item => item.y),
                                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                                    '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'
                                ],
                                borderWidth: 0,
                                hoverOffset: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: '#9CA3AF',
                                        font: {
                                            size: 10
                                        },
                                        padding: 10,
                                        boxWidth: 10
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#9CA3AF',
                                    callbacks: {
                                        label: (context) => {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b,
                                                0);
                                            const percentage = total > 0 ? Math.round((value / total) *
                                                100) : 0;
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            cutout: '60%',
                            layout: {
                                padding: {
                                    bottom: 10
                                }
                            }
                        }
                    });
                }
            }

            initCharts(initialBarData, initialPieData);

            document.getElementById('tahunFilter').addEventListener('change', function() {
                window.location.href = '?tahun=' + this.value;
            });

            document.getElementById('refreshData').addEventListener('click', function() {
                const tahun = document.getElementById('tahunFilter').value;
                const button = this;
                const originalHtml = button.innerHTML;
                button.innerHTML =
                    '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>';
                button.disabled = true;
                fetch(`{{ route('dashboard.chart-data') }}?tahun=${tahun}`)
                    .then(response => response.json())
                    .then(data => {
                        initCharts(data.barData, data.pieData);
                        updateMainCards(data);
                        updateStatusTable(data.statusData);
                        updateRecentTickets(data.recentTickets);
                        updateYearDisplays(tahun);
                        const totalElement = document.getElementById('totalTahunIni');
                        if (totalElement) totalElement.textContent = data.total;
                    })
                    .catch(error => console.error('Error:', error))
                    .finally(() => {
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                    });
            });
        });
    </script>
@endpush
