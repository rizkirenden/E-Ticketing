@extends('layouts.app')

@section('title', 'Detail Laporan - ' . $laporan->nomor_ticket)

@section('content')
    <div class="h-screen overflow-hidden bg-gradient-to-br from-[#001D39] via-[#002B4F] to-[#001D39]">
        <div class="h-full overflow-y-auto scrollbar-thin">
            <div class="relative">
                <!-- Animated Background -->
                <div class="absolute inset-0 opacity-10 pointer-events-none hidden md:block">
                    <div class="absolute top-0 -left-4 w-72 h-72 animate-float-slow">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert">
                    </div>
                    <div class="absolute top-1/3 -right-4 w-64 h-64 animate-float">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-70">
                    </div>
                    <div class="absolute bottom-0 left-1/4 w-80 h-80 animate-float-delayed">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-50">
                    </div>
                    <div class="absolute top-1/2 left-10 w-48 h-48 animate-spin-slow">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-30">
                    </div>
                    <div class="absolute bottom-1/4 right-10 w-56 h-56 animate-bounce-slow">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-40">
                    </div>
                    <div class="absolute top-20 right-1/4 w-32 h-32 animate-pulse-slow">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-20">
                    </div>
                    <div class="absolute bottom-40 left-20 w-40 h-40 animate-float-reverse">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert opacity-25">
                    </div>
                    <div class="absolute top-40 left-1/3 w-24 h-24 animate-spin-slow opacity-15">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert">
                    </div>
                    <div class="absolute bottom-32 right-1/3 w-28 h-28 animate-float opacity-20">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert">
                    </div>
                </div>

                <!-- Header -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center group">
                            <img src="{{ asset('assets/logo1.PNG') }}" alt="Logo E-Ticketing"
                                class="h-8 md:h-13 w-auto brightness-0 invert drop-shadow-2xl transform group-hover:scale-110 transition-transform duration-500"
                                onerror="this.style.display='none'">
                        </div>
                        <div class="flex items-center space-x-1 md:space-x-2">
                            <span class="text-white text-xs md:text-base font-medium">DEVELOP BY</span>
                            <span
                                class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent font-bold text-sm md:text-lg">IT
                                DIGITAL</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12">
                    <!-- Header Card -->
                    <div
                        class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 backdrop-blur-xl border border-white/10 rounded-2xl md:rounded-3xl p-4 md:p-8 mb-6 md:mb-8">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 md:gap-6">
                            <div class="flex items-center gap-3 md:gap-4">
                                <div
                                    class="w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl md:rounded-2xl shadow-2xl flex items-center justify-center">
                                    <svg class="w-6 h-6 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-1 md:mb-2">
                                        <h1 class="text-xl md:text-3xl lg:text-4xl font-bold text-white">Detail Laporan</h1>
                                    </div>
                                    <div class="flex items-center gap-1 md:gap-2 text-gray-400">
                                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                            </path>
                                        </svg>
                                        <span class="text-xs md:text-lg font-mono">{{ $laporan->nomor_ticket }}</span>
                                    </div>
                                </div>
                            </div>
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'process' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'done' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'reject' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    'pending' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    'escalate' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                ];
                                $statusColor =
                                    $statusColors[strtolower($laporan->status)] ??
                                    'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                $statusIcon = [
                                    'open' => '🟡',
                                    'process' => '🔄',
                                    'done' => '✅',
                                    'reject' => '❌',
                                    'pending' => '⏳',
                                    'escalate' => '⬆️',
                                ];
                                $icon = $statusIcon[strtolower($laporan->status)] ?? '•';
                                $statusText = [
                                    'open' => 'OPEN',
                                    'process' => 'PROCESS',
                                    'done' => 'DONE',
                                    'reject' => 'REJECT',
                                    'pending' => 'PENDING',
                                    'escalate' => 'ESCALATE',
                                ];
                                $statusLabel =
                                    $statusText[strtolower($laporan->status)] ?? strtoupper($laporan->status);
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-2 h-2 rounded-full animate-pulse {{ $laporan->status == 'open' ? 'bg-yellow-400' : ($laporan->status == 'process' ? 'bg-blue-400' : ($laporan->status == 'done' ? 'bg-green-400' : ($laporan->status == 'reject' ? 'bg-red-400' : ($laporan->status == 'pending' ? 'bg-orange-400' : ($laporan->status == 'escalate' ? 'bg-purple-400' : 'bg-gray-400'))))) }}">
                                    </div>
                                    <span
                                        class="{{ $statusColor }} px-4 md:px-6 py-2 md:py-2.5 rounded-full text-sm md:text-base font-semibold border shadow-lg">
                                        {{ $icon }} {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid lg:grid-cols-3 gap-4 md:gap-8">
                        <!-- Left Column -->
                        <div class="lg:col-span-2 space-y-4 md:space-y-6">
                            <!-- Data Pelapor -->
                            <div
                                class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                <h2
                                    class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Data Pelapor
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                    <div class="space-y-1">
                                        <p class="text-gray-400 text-xs md:text-sm">Nama Lengkap</p>
                                        <p class="text-white font-medium text-base md:text-lg">{{ $laporan->nama_pelapor }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-gray-400 text-xs md:text-sm">Nomor Handphone</p>
                                        <p class="text-white font-medium text-sm md:text-base">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $laporan->no_handphone) }}"
                                                class="hover:text-green-400 transition-colors inline-flex items-center gap-1">
                                                {{ $laporan->no_handphone }}
                                                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-gray-400 text-xs md:text-sm">Kantor/Cabang</p>
                                        <p class="text-white font-medium text-sm md:text-base">
                                            {{ $laporan->kantor->nama_cabang ?? 'N/A' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-gray-400 text-xs md:text-sm">Aplikasi Terkait</p>
                                        <p class="text-white font-medium text-sm md:text-base">
                                            {{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kronologi -->
                            <div
                                class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                <h2
                                    class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kronologi Kejadian
                                </h2>
                                <div class="bg-white/5 rounded-lg md:rounded-xl p-3 md:p-4">
                                    <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                                        {!! nl2br(e($laporan->kronologi)) !!}
                                    </p>
                                </div>
                            </div>

                            <!-- Riwayat Alasan & Tindakan (TIMELINE YANG BERTAMBAH) -->
                            <div
                                class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                <h2
                                    class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    Riwayat Alasan & Tindakan
                                    @if (count($alasanTimeline) > 0)
                                        <span class="text-xs text-gray-500">({{ count($alasanTimeline) }} catatan)</span>
                                    @endif
                                </h2>

                                <div class="relative">
                                    <div
                                        class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-orange-500 via-purple-500 to-green-500 rounded-full">
                                    </div>

                                    <div class="space-y-5">
                                        @forelse($alasanTimeline as $alasan)
                                            <div class="relative pl-12 group">
                                                <div class="absolute left-0 top-0">
                                                    <div
                                                        class="w-8 h-8 rounded-full
                                                        @switch($alasan['color'] ?? 'gray')
                                                            @case('orange') bg-orange-500/20 ring-orange-500/30 @break
                                                            @case('purple') bg-purple-500/20 ring-purple-500/30 @break
                                                            @case('green') bg-green-500/20 ring-green-500/30 @break
                                                            @default bg-gray-500/20 ring-gray-500/30
                                                        @endswitch
                                                        ring-4 flex items-center justify-center">
                                                        <span class="text-sm">{{ $alasan['icon'] ?? '📌' }}</span>
                                                    </div>
                                                </div>

                                                <div
                                                    class="bg-white/5 rounded-lg p-3 hover:bg-white/10 transition-all duration-300">
                                                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-white font-medium text-sm">
                                                                @if ($alasan['status'] == 'pending')
                                                                    ⏳ STATUS DITUNDA
                                                                @elseif($alasan['status'] == 'escalate')
                                                                    ⬆️ STATUS ESCALASI
                                                                @elseif($alasan['status'] == 'done')
                                                                    ✅ SOLUSI
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <span class="text-gray-400 text-xs">
                                                            {{ \Carbon\Carbon::parse($alasan['timestamp'])->format('d M Y H:i:s') }}
                                                        </span>
                                                    </div>

                                                    <div
                                                        class="mt-2 p-2 rounded-lg
                                                        @if ($alasan['status'] == 'pending') bg-orange-500/10 border-l-4 border-orange-500
                                                        @elseif($alasan['status'] == 'escalate') bg-purple-500/10 border-l-4 border-purple-500
                                                        @elseif($alasan['status'] == 'done') bg-green-500/10 border-l-4 border-green-500 @endif">
                                                        <p class="text-gray-300 text-xs md:text-sm">
                                                            {{ $alasan['description'] }}
                                                        </p>
                                                    </div>

                                                    @if ($alasan['status'] != 'done')
                                                        <div class="mt-2 flex items-center gap-1 text-gray-500 text-xs">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                </path>
                                                            </svg>
                                                            <span>Diubah oleh:
                                                                {{ $alasan['updated_by'] ?? 'System' }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-gray-500 py-8">
                                                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm">Belum ada alasan atau tindakan yang tercatat</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Lampiran -->
                            @if (!empty($lampiran))
                                <div
                                    class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                    <h2
                                        class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        Lampiran
                                    </h2>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-2 md:gap-3">
                                        @foreach ($lampiran as $file)
                                            @php
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), [
                                                    'jpg',
                                                    'jpeg',
                                                    'png',
                                                    'gif',
                                                    'bmp',
                                                ]);
                                            @endphp
                                            <a href="{{ Storage::url($file) }}" target="_blank"
                                                class="group relative aspect-square bg-white/5 rounded-lg md:rounded-xl overflow-hidden border border-white/10 hover:border-blue-400/50 transition-all">
                                                @if ($isImage)
                                                    <img src="{{ Storage::url($file) }}" alt="Lampiran"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div
                                                        class="w-full h-full flex flex-col items-center justify-center gap-1 md:gap-2">
                                                        <svg class="w-5 h-5 md:w-8 md:h-8 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                        <span
                                                            class="text-[10px] md:text-xs text-gray-400">{{ strtoupper($extension) }}</span>
                                                    </div>
                                                @endif
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-1 md:p-2">
                                                    <span class="text-[10px] md:text-xs text-white">Lihat Detail</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4 md:space-y-6">
                            <!-- Status Card -->
                            <div
                                class="bg-gradient-to-br from-blue-600/20 to-purple-600/20 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                <h2
                                    class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Status Laporan
                                </h2>

                                <div class="space-y-4 md:space-y-6">
                                    <div class="bg-white/5 rounded-xl p-4">
                                        <div class="flex flex-col items-center text-center">
                                            <div class="relative mb-3">
                                                <div
                                                    class="absolute inset-0 rounded-full blur-xl {{ $laporan->status == 'open' ? 'bg-yellow-500/30' : ($laporan->status == 'process' ? 'bg-blue-500/30' : ($laporan->status == 'done' ? 'bg-green-500/30' : ($laporan->status == 'reject' ? 'bg-red-500/30' : ($laporan->status == 'pending' ? 'bg-orange-500/30' : ($laporan->status == 'escalate' ? 'bg-purple-500/30' : 'bg-gray-500/30'))))) }}">
                                                </div>
                                                <div
                                                    class="relative w-16 h-16 rounded-full bg-gradient-to-br {{ $laporan->status == 'open' ? 'from-yellow-500 to-yellow-600' : ($laporan->status == 'process' ? 'from-blue-500 to-blue-600' : ($laporan->status == 'done' ? 'from-green-500 to-green-600' : ($laporan->status == 'reject' ? 'from-red-500 to-red-600' : ($laporan->status == 'pending' ? 'from-orange-500 to-orange-600' : ($laporan->status == 'escalate' ? 'from-purple-500 to-purple-600' : 'from-gray-500 to-gray-600'))))) }} shadow-lg flex items-center justify-center">
                                                    <span class="text-3xl">{{ $icon }}</span>
                                                </div>
                                            </div>
                                            <span
                                                class="{{ $statusColor }} px-4 py-1.5 rounded-full text-sm font-semibold border">{{ $statusLabel }}</span>
                                        </div>
                                    </div>

                                    <!-- Timeline Status (Tanpa Diubah oleh) -->
                                    <div class="mt-4">
                                        <h3 class="text-sm font-medium text-gray-400 mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Timeline Laporan
                                            @if (count($timeline) > 0)
                                                <span class="text-xs text-gray-500">({{ count($timeline) }}
                                                    perubahan)</span>
                                            @endif
                                        </h3>

                                        <div class="relative">
                                            <!-- Garis vertikal timeline -->
                                            <div
                                                class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gradient-to-b from-blue-500 via-purple-500 to-green-500 rounded-full">
                                            </div>

                                            <div class="space-y-4">
                                                @forelse($timeline as $index => $event)
                                                    <div class="relative flex gap-3">
                                                        <!-- Timeline dot -->
                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="w-8 h-8 rounded-full
                            @switch($event['color'] ?? 'gray')
                                @case('yellow') bg-yellow-500/20 ring-yellow-500/30 @break
                                @case('blue') bg-blue-500/20 ring-blue-500/30 @break
                                @case('green') bg-green-500/20 ring-green-500/30 @break
                                @case('red') bg-red-500/20 ring-red-500/30 @break
                                @case('orange') bg-orange-500/20 ring-orange-500/30 @break
                                @case('purple') bg-purple-500/20 ring-purple-500/30 @break
                                @default bg-gray-500/20 ring-gray-500/30
                            @endswitch
                            ring-4 flex items-center justify-center">
                                                                <span class="text-sm">{{ $event['icon'] ?? '📌' }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- Content -->
                                                        <div
                                                            class="flex-1 bg-white/5 rounded-lg p-3 hover:bg-white/10 transition-all duration-300">
                                                            <!-- Status -->
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                @if (!empty($event['old_status_label']) && !empty($event['new_status_label']))
                                                                    <span class="text-white font-medium text-sm">
                                                                        {{ $event['old_status_label'] }}
                                                                        <svg class="inline w-3 h-3 mx-1" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                                        </svg>
                                                                        {{ $event['new_status_label'] }}
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="text-white font-medium text-sm">{{ $event['new_status_label'] ?? ucfirst($event['new_status'] ?? '') }}</span>
                                                                @endif
                                                                @if (!empty($event['is_initial']))
                                                                    <span
                                                                        class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded-full">Awal</span>
                                                                @endif
                                                            </div>

                                                            <!-- Tanggal di bawah -->
                                                            <div class="mt-2">
                                                                <span class="text-gray-500 text-xs">
                                                                    @if (!empty($event['timestamp']))
                                                                        {{ \Carbon\Carbon::parse($event['timestamp'])->format('d M Y H:i:s') }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center text-gray-400 py-8">
                                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <p>Belum ada perubahan status</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Card -->
                            <div
                                class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                                <h2
                                    class="text-lg md:text-xl font-semibold text-white mb-3 md:mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Tambahan
                                </h2>
                                <div class="space-y-2 md:space-y-3">
                                    <div class="flex justify-between py-1 md:py-2 border-b border-white/10">
                                        <span class="text-gray-400 text-xs md:text-sm">Nomor Tiket</span>
                                        <span
                                            class="text-white text-xs md:text-sm font-mono">{{ $laporan->nomor_ticket }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 md:py-2 border-b border-white/10">
                                        <span class="text-gray-400 text-xs md:text-sm">Tanggal Laporan</span>
                                        <span
                                            class="text-white text-xs md:text-sm">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between py-1 md:py-2">
                                        <span class="text-gray-400 text-xs md:text-sm">Terakhir Update</span>
                                        <span
                                            class="text-white text-xs md:text-sm">{{ $laporan->updated_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>

                                <div class="pt-3 md:pt-4 mt-2 border-t border-white/10">
                                    <a href="{{ route('detail_laporan.index') }}"
                                        class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold py-2 md:py-3 px-3 md:px-4 rounded-xl transition-all transform hover:scale-[1.02] hover:shadow-xl flex items-center justify-center gap-2 group text-sm md:text-base">
                                        <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:-translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        <span>Kembali ke Daftar Laporan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-20px) rotate(5deg);
                }
            }

            @keyframes float-slow {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-30px) rotate(-5deg);
                }
            }

            @keyframes float-delayed {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-25px) rotate(10deg);
                }
            }

            @keyframes float-reverse {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(15px) rotate(-8deg);
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

            @keyframes bounce-slow {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                }

                50% {
                    transform: translateY(-15px) scale(1.05);
                }
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 0.2;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.4;
                    transform: scale(1.1);
                }
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-float-slow {
                animation: float-slow 8s ease-in-out infinite;
            }

            .animate-float-delayed {
                animation: float-delayed 7s ease-in-out infinite;
            }

            .animate-float-reverse {
                animation: float-reverse 5s ease-in-out infinite;
            }

            .animate-spin-slow {
                animation: spin-slow 20s linear infinite;
            }

            .animate-bounce-slow {
                animation: bounce-slow 4s ease-in-out infinite;
            }

            .animate-pulse-slow {
                animation: pulse-slow 3s ease-in-out infinite;
            }

            .scrollbar-thin::-webkit-scrollbar {
                width: 8px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 10px;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }

            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            .h-screen {
                height: 100vh;
                overflow: hidden;
            }

            .overflow-y-auto {
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch;
                scroll-behavior: smooth;
            }

            .pointer-events-none {
                pointer-events: none;
            }

            @media (max-width: 768px) {
                .px-4.sm\:px-6.lg\:px-8 {
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }

                .rounded-2xl.p-4 {
                    padding: 1rem !important;
                }

                .w-12.h-12 {
                    width: 2.5rem !important;
                    height: 2.5rem !important;
                }

                .grid-cols-2.sm\:grid-cols-3 {
                    grid-template-columns: repeat(2, 1fr) !important;
                }

                .scrollbar-thin::-webkit-scrollbar {
                    width: 4px;
                }

                .pl-12 {
                    padding-left: 2rem !important;
                }

                .left-2 {
                    left: 0.25rem !important;
                }
            }
        </style>
    @endpush
@endsection
