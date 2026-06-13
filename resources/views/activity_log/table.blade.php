<!-- Activity Logs Table -->
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <!-- Header dengan Filter dan Tombol Export -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4 pb-4 border-b border-white/10">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <h3 class="text-white font-semibold text-lg">Activity Logs</h3>
            <span class="bg-white/10 text-gray-300 px-2 py-0.5 rounded text-xs">{{ $logs->total() }} total</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">User</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aktivitas</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Deskripsi</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Modul</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Data ID</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Tanggal</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <td class="py-3 px-4">
                            @if ($log->user)
                                <div class="text-gray-300 text-sm font-medium">{{ $log->user->nama }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->username }}</div>
                            @else
                                <span class="text-gray-400 text-sm">System</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $activityColors = [
                                    'CREATE' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'UPDATE' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'DELETE' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    'LOGIN' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                    'LOGOUT' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'LOGIN_FAILED' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    'WHATSAPP' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'EXPORT_EXCEL' => 'bg-teal-500/20 text-teal-400 border-teal-500/30',
                                ];
                                $activityColor =
                                    $activityColors[$log->aktivitas] ??
                                    'bg-gray-500/20 text-gray-400 border-gray-500/30';

                                $activityIcon = [
                                    'CREATE' => '➕',
                                    'UPDATE' => '✏️',
                                    'DELETE' => '🗑️',
                                    'LOGIN' => '🔐',
                                    'LOGOUT' => '👋',
                                    'LOGIN_FAILED' => '❌',
                                    'WHATSAPP' => '📱',
                                    'EXPORT_EXCEL' => '📊',
                                ];
                                $icon = $activityIcon[$log->aktivitas] ?? '•';
                            @endphp
                            <span
                                class="{{ $activityColor }} px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1 border">
                                <span>{{ $icon }}</span>
                                <span>{{ $log->aktivitas }}</span>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-300 text-sm max-w-xs line-clamp-2" title="{{ $log->deskripsi }}">
                                {{ $log->deskripsi }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span
                                class="bg-white/5 text-gray-300 px-3 py-1.5 rounded-lg text-xs border border-white/10">
                                {{ $log->modul }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-gray-300 text-sm font-mono bg-blue-500/10 px-2 py-1 rounded">
                                {{ $log->data_id ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-300 text-sm">{{ $log->tanggal_aktivitas->format('d/m/Y H:i:s') }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <button onclick="viewLogDetail({{ $log->id }})"
                                    class="text-blue-400 hover:text-blue-300 transition-colors p-2 hover:bg-blue-400/10 rounded-lg group"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada activity logs</p>
                                <p class="text-xs text-gray-500 max-w-md">Belum ada aktivitas yang tercatat dalam sistem
                                </p>
                                <button onclick="resetFilters()"
                                    class="mt-2 bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 px-4 py-2 rounded-lg text-sm transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Reset Filter
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if (method_exists($logs, 'links') && $logs->hasPages())
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/10 pt-4">
            <p class="text-gray-400 text-sm order-2 sm:order-1">
                Menampilkan
                <span class="text-white font-medium">{{ $logs->firstItem() ?? 0 }}</span>
                -
                <span class="text-white font-medium">{{ $logs->lastItem() ?? 0 }}</span>
                dari
                <span class="text-white font-medium">{{ $logs->total() }}</span>
                data
            </p>

            <div class="flex items-center gap-2 order-1 sm:order-2">
                @if ($logs->onFirstPage())
                    <span
                        class="px-3 py-1.5 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed flex items-center gap-1 border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}"
                        class="pagination-link px-3 py-1.5 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-1 border border-white/10 hover:border-blue-400/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </a>
                @endif

                <div class="flex items-center gap-1">
                    @php
                        $totalPages = $logs->lastPage();
                        $currentPage = $logs->currentPage();

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
                        <a href="{{ $logs->url(1) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm border border-white/10 hover:border-blue-400/30">1</a>
                        @if ($start > 2)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $logs->url($i) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center text-sm rounded-lg transition-all border
                            {{ $logs->currentPage() == $i ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium shadow-lg shadow-blue-500/25 border-transparent' : 'bg-white/5 text-gray-300 hover:bg-white/10 border-white/10 hover:border-blue-400/30' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($end < $totalPages)
                        @if ($end < $totalPages - 1)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $logs->url($totalPages) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm border border-white/10 hover:border-blue-400/30">{{ $totalPages }}</a>
                    @endif
                </div>

                @if ($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}"
                        class="pagination-link px-3 py-1.5 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-1 border border-white/10 hover:border-blue-400/30">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                @else
                    <span
                        class="px-3 py-1.5 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed flex items-center gap-1 border border-white/10">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover td {
            color: #fff;
        }

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

        .pagination-link {
            transition: all 0.2s ease;
        }
    </style>
@endpush
