@extends('layouts.app')

@section('title', 'Detail Laporan - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        <!-- Include sidebar -->
        @include('layouts.sidebar')

        <!-- Konten halaman dengan scroll -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto" style="height: 100vh;">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Detail Laporan</h1>
                    <p class="text-sm sm:text-base text-gray-400">Informasi lengkap laporan tiket</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('laporan.index') }}"
                        class="w-full sm:w-auto px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-sm text-center">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-6">
                @php
                    $statusColors = [
                        'open' => 'bg-yellow-500/20 text-yellow-400',
                        'process' => 'bg-blue-500/20 text-blue-400',
                        'done' => 'bg-green-500/20 text-green-400',
                        'reject' => 'bg-red-500/20 text-red-400',
                        'pending' => 'bg-orange-500/20 text-orange-400',
                        'escalate' => 'bg-purple-500/20 text-purple-400',
                    ];
                    $statusIcons = [
                        'open' => '🟡',
                        'process' => '🔄',
                        'done' => '✓',
                        'reject' => '✗',
                        'pending' => '⏳',
                        'escalate' => '⬆️',
                    ];
                    $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-500/20 text-gray-400';
                    $statusIcon = $statusIcons[$laporan->status] ?? '•';
                @endphp
                <span
                    class="{{ $statusColor }} px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium inline-flex items-center gap-2">
                    <span>{{ $statusIcon }}</span>
                    <span class="uppercase">Status: {{ $laporan->status }}</span>
                </span>
            </div>

            <!-- Detail Card -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Nomor Ticket -->
                    <div class="bg-blue-500/5 rounded-lg p-3 sm:p-4 border border-blue-500/20">
                        <label class="block text-xs font-medium text-blue-400 mb-1">Nomor Ticket</label>
                        <p class="text-white text-base sm:text-lg font-mono font-bold break-all">
                            {{ $laporan->nomor_ticket }}</p>
                        @php
                            $ticketParts = explode('-', $laporan->nomor_ticket);
                        @endphp
                    </div>

                    <!-- Nama Pelapor -->
                    <div class="bg-white/5 rounded-lg p-3 sm:p-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-1">Nama Pelapor</label>
                        <p class="text-white text-base sm:text-lg break-words">{{ $laporan->nama_pelapor }}</p>
                    </div>

                    <!-- No Handphone -->
                    <div class="bg-white/5 rounded-lg p-3 sm:p-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-1">No. Handphone</label>
                        <p class="text-white text-base sm:text-lg break-all">{{ $laporan->no_handphone }}</p>
                    </div>

                    <!-- Kantor -->
                    <div class="bg-white/5 rounded-lg p-3 sm:p-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-1">Kantor</label>
                        <p class="text-white text-base sm:text-lg break-words">{{ $laporan->kantor->kode_cabang ?? 'N/A' }}
                            -
                            {{ $laporan->kantor->nama_cabang ?? 'N/A' }}</p>
                    </div>

                    <!-- Jenis Aplikasi -->
                    <div
                        class="bg-gradient-to-r from-purple-500/10 to-blue-500/10 rounded-lg p-3 sm:p-4 border border-purple-500/20">
                        <label class="block text-xs sm:text-sm font-medium text-purple-400 mb-2">
                            <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9h14M5 15h14M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z">
                                </path>
                            </svg>
                            Jenis Aplikasi
                        </label>
                        <div class="space-y-1 sm:space-y-2">
                            <p class="text-white text-base sm:text-lg font-semibold break-words">
                                {{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}
                            </p>
                            <p class="text-gray-400 text-xs sm:text-sm">
                                <span class="text-purple-400">Kode Aplikasi:</span>
                                {{ $laporan->jenisAplikasi->kode_jenis_aplikasi ?? 'N/A' }}
                            </p>
                            @if ($laporan->jenisAplikasi)
                                <p class="text-gray-400 text-xs mt-1">{{ $laporan->jenisAplikasi->deskripsi }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Kode/Nama Produk -->
                    <div
                        class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-lg p-3 sm:p-4 border border-green-500/20">
                        <label class="block text-xs sm:text-sm font-medium text-green-400 mb-2">
                            <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Kode/Nama Produk
                        </label>
                        <div class="space-y-1 sm:space-y-2">
                            <p class="text-white text-base sm:text-lg font-semibold break-words">
                                {{ $laporan->produk->nama_produk ?? 'N/A' }}
                            </p>
                            <p class="text-gray-400 text-xs sm:text-sm">
                                <span class="text-green-400">Kode Produk:</span>
                                {{ $laporan->produk->kode_produk ?? 'N/A' }}
                            </p>
                            @if ($laporan->produk)
                                <p class="text-gray-400 text-xs mt-1">
                                    {{ $laporan->produk->deskripsi ?? 'Tidak ada deskripsi' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Tanggal Laporan -->
                    <div class="bg-white/5 rounded-lg p-3 sm:p-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-1">Tanggal Laporan</label>
                        <p class="text-white text-base sm:text-lg">{{ $laporan->tanggal_laporan->format('d/m/Y H:i') }} WIB
                        </p>
                    </div>

                    <!-- Tanggal Selesai -->
                    @if ($laporan->tanggal_selesai)
                        <div class="bg-white/5 rounded-lg p-3 sm:p-4">
                            <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-1">Tanggal Selesai</label>
                            <p class="text-white text-base sm:text-lg">{{ $laporan->tanggal_selesai->format('d/m/Y H:i') }}
                                WIB</p>
                        </div>
                    @endif

                    <!-- Kronologi (Full Width) -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-2">Kronologi</label>
                        <div
                            class="bg-white/5 border border-white/10 rounded-lg p-3 sm:p-4 text-white whitespace-pre-line text-sm sm:text-base break-words">
                            {{ $laporan->kronologi }}
                        </div>
                    </div>

                    <!-- Pending Deskripsi -->
                    @if ($laporan->status == 'pending' && $laporan->pending_deskripsi)
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-orange-400 mb-2">
                                <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Alasan Pending
                            </label>
                            <div
                                class="bg-orange-500/10 border border-orange-500/20 rounded-lg p-3 sm:p-4 text-white whitespace-pre-line text-sm sm:text-base break-words">
                                {{ $laporan->pending_deskripsi }}
                            </div>
                        </div>
                    @endif

                    <!-- Escalate Deskripsi -->
                    @if ($laporan->status == 'escalate' && $laporan->escalate_deskripsi)
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-purple-400 mb-2">
                                <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Alasan Escalate
                            </label>
                            <div
                                class="bg-purple-500/10 border border-purple-500/20 rounded-lg p-3 sm:p-4 text-white whitespace-pre-line text-sm sm:text-base break-words">
                                {{ $laporan->escalate_deskripsi }}
                            </div>
                        </div>
                    @endif

                    <!-- Solusi -->
                    @if ($laporan->solusi)
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-green-400 mb-2">
                                <svg class="inline w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Solusi
                            </label>
                            <div
                                class="bg-green-500/10 border border-green-500/20 rounded-lg p-3 sm:p-4 text-white whitespace-pre-line text-sm sm:text-base break-words">
                                {{ $laporan->solusi }}
                            </div>
                        </div>
                    @endif

                    <!-- LAMPIRAN -->
                    @if ($laporan->lampiran && count($laporan->lampiran) > 0)
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-2">Lampiran
                                ({{ count($laporan->lampiran) }} file)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                                @foreach ($laporan->lampiran as $index => $file)
                                    @php
                                        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                        $fileName = basename($file);
                                        $fileUrl = Storage::url($file);
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank"
                                        class="group relative bg-white/5 border border-white/10 rounded-lg p-2 sm:p-3 hover:border-green-500/50 transition-all duration-300 overflow-hidden">
                                        @if ($isImage)
                                            <div class="aspect-w-1 aspect-h-1 mb-2">
                                                <img src="{{ $fileUrl }}" alt="Lampiran"
                                                    class="w-full h-20 sm:h-24 object-cover rounded-lg group-hover:scale-110 transition-transform duration-300">
                                            </div>
                                            <p class="text-xs text-gray-300 truncate text-center">{{ $fileName }}</p>
                                        @elseif ($extension == 'pdf')
                                            <div class="text-center">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-red-400 mx-auto mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-xs text-gray-300 truncate">{{ $fileName }}</p>
                                            </div>
                                        @elseif (in_array($extension, ['doc', 'docx']))
                                            <div class="text-center">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-blue-400 mx-auto mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-xs text-gray-300 truncate">{{ $fileName }}</p>
                                            </div>
                                        @elseif (in_array($extension, ['xls', 'xlsx']))
                                            <div class="text-center">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-green-400 mx-auto mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-xs text-gray-300 truncate">{{ $fileName }}</p>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-xs text-gray-300 truncate">{{ $fileName }}</p>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-green-500/0 group-hover:bg-green-500/10 rounded-lg transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                            <span class="bg-black/50 text-white text-xs px-2 py-1 rounded-full">
                                                Klik untuk lihat
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-400 mb-2">Lampiran</label>
                            <div class="bg-white/5 border border-white/10 rounded-lg p-6 sm:p-8 text-gray-400 text-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-500 mx-auto mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <p class="text-sm sm:text-base">Tidak ada lampiran</p>
                            </div>
                        </div>
                    @endif

                    <!-- Info Created/Updated -->
                    <div class="col-span-1 md:col-span-2 border-t border-white/10 pt-4 mt-2">
                        <div class="flex flex-wrap gap-4 sm:gap-6 text-xs text-gray-400">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Dibuat: {{ $laporan->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Diupdate: {{ $laporan->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar */
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.3);
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.6);
        }

        /* Memastikan konten tidak terpotong */
        .flex-1 {
            min-height: 0;
        }

        /* Animation untuk hover effect */
        .group-hover\:bg-green-500\/10 {
            transition: all 0.3s ease;
        }

        .group-hover\:scale-110 {
            transition: transform 0.3s ease;
        }

        /* Mobile touch improvements */
        @media (max-width: 640px) {
            .group-hover\:opacity-100 {
                opacity: 1 !important;
            }

            .group .absolute {
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
            }

            .group:active .absolute {
                opacity: 1;
            }
        }

        /* Break word untuk text panjang di mobile */
        .break-words {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .break-all {
            word-break: break-all;
        }
    </style>
@endpush
