<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">No. Ticket</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Pelapor</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Kantor</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aplikasi</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Kronologi</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Status</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Tanggal</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <td class="py-3 px-4">
                            <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-lg text-xs font-mono">
                                {{ $laporan->nomor_ticket }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm">
                            <div class="font-medium">{{ $laporan->nama_pelapor }}</div>
                            <div class="text-xs text-gray-400">{{ $laporan->no_handphone }}</div>
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm">
                            {{ $laporan->kantor->nama_cabang ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm">
                            {{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm max-w-xs">
                            <div class="line-clamp-2 text-xs leading-relaxed" title="{{ $laporan->kronologi }}">
                                {{ Str::limit($laporan->kronologi, 60) }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'process' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'done' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'reject' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                ];
                                $statusLabels = [
                                    'open' => 'OPEN',
                                    'process' => 'PROCESS',
                                    'done' => 'DONE',
                                    'reject' => 'REJECT',
                                ];
                                $statusColor =
                                    $statusColors[$laporan->status] ??
                                    'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                $statusLabel = $statusLabels[$laporan->status] ?? strtoupper($laporan->status);
                            @endphp
                            <span
                                class="{{ $statusColor }} px-3 py-1 rounded-lg text-xs font-medium uppercase border inline-block">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm">
                            <div>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d/m/Y') }}</div>
                            @if ($laporan->tanggal_selesai)
                                <div class="text-xs text-gray-400">
                                    Selesai: {{ \Carbon\Carbon::parse($laporan->tanggal_selesai)->format('d/m/Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('detail_laporan.show', $laporan->id) }}"
                                class="text-blue-400 hover:text-blue-300 transition-colors p-2 hover:bg-blue-400/10 rounded-lg inline-flex items-center gap-1 group">
                                <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-14 0 3 3 0 0114 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span class="text-sm">Detail</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada laporan yang ditemukan</p>
                                <p class="text-xs text-gray-500 max-w-md">
                                    Coba atur ulang filter atau kata kunci pencarian untuk melihat data laporan
                                </p>
                                <button onclick="resetFilters()"
                                    class="mt-2 bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Reset Semua Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if (method_exists($laporans, 'links') && $laporans->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/10 pt-4">
            <p class="text-gray-400 text-sm order-2 sm:order-1">
                Menampilkan
                <span class="text-white font-medium">{{ $laporans->firstItem() ?? 0 }}</span>
                -
                <span class="text-white font-medium">{{ $laporans->lastItem() ?? 0 }}</span>
                dari
                <span class="text-white font-medium">{{ $laporans->total() }}</span>
                data
            </p>

            <div class="flex items-center gap-2 order-1 sm:order-2">
                <!-- Previous Page Link -->
                @if ($laporans->onFirstPage())
                    <span
                        class="px-3 py-1.5 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </span>
                @else
                    <a href="{{ $laporans->previousPageUrl() }}"
                        class="pagination-link px-3 py-1.5 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </a>
                @endif

                <!-- Pagination Elements -->
                <div class="flex items-center gap-1">
                    @php
                        $totalPages = $laporans->lastPage();
                        $currentPage = $laporans->currentPage();

                        if ($totalPages <= 5) {
                            $start = 1;
                            $end = $totalPages;
                        } else {
                            if ($currentPage <= 3) {
                                $start = 1;
                                $end = 5;
                            } elseif ($currentPage >= $totalPages - 2) {
                                $start = $totalPages - 4;
                                $end = $totalPages;
                            } else {
                                $start = $currentPage - 2;
                                $end = $currentPage + 2;
                            }
                        }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $laporans->url(1) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm">
                            1
                        </a>
                        @if ($start > 2)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $laporans->url($i) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center text-sm rounded-lg transition-all
                                {{ $laporans->currentPage() == $i
                                    ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium shadow-lg shadow-blue-500/25'
                                    : 'bg-white/5 text-gray-300 hover:bg-white/10' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($end < $totalPages)
                        @if ($end < $totalPages - 1)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $laporans->url($totalPages) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm">
                            {{ $totalPages }}
                        </a>
                    @endif
                </div>

                <!-- Next Page Link -->
                @if ($laporans->hasMorePages())
                    <a href="{{ $laporans->nextPageUrl() }}"
                        class="pagination-link px-3 py-1.5 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-1">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                @else
                    <span
                        class="px-3 py-1.5 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed flex items-center gap-1">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="mt-3 text-xs text-gray-500 text-center sm:text-left">
            <p>Total halaman: {{ $laporans->lastPage() }} | Data per halaman: {{ $laporans->perPage() }}</p>
        </div>
    @endif
</div>

@push('styles')
    <style>
        /* Line clamp untuk kronologi */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Hover effect untuk row */
        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover td {
            color: #fff;
        }

        /* Custom scrollbar untuk overflow-x */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Animasi untuk loading state */
        .pagination-link {
            transition: all 0.2s ease;
        }

        /* Styling untuk badge status */
        .status-badge {
            position: relative;
            overflow: hidden;
        }

        .status-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
        }

        .status-badge:hover::after {
            transform: translateX(100%);
            transition: transform 0.5s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Inisialisasi tooltip jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alert setelah 5 detik
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endpush
