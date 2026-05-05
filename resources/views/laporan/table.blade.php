<!-- Laporan Table - Fully Responsive (No Horizontal Scroll) -->
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-3 sm:p-4 md:p-6 overflow-hidden">
    <!-- Tabel Container dengan overflow-x hanya jika benar-benar perlu -->
    <div class="overflow-x-auto overflow-y-visible">
        <table class="w-full table-auto">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2">No. Ticket</th>
                    <th class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2">Pelapor</th>
                    <th
                        class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2 hidden sm:table-cell">
                        Kantor</th>
                    <th
                        class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2 hidden md:table-cell">
                        Aplikasi</th>
                    <th
                        class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2 hidden lg:table-cell">
                        Produk</th>
                    <th class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2">Status</th>
                    <th
                        class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2 hidden lg:table-cell">
                        Tanggal</th>
                    <th class="text-left text-gray-400 text-[10px] sm:text-xs font-medium py-2 px-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <!-- No. Ticket -->
                        <td class="py-2 px-2">
                            <span
                                class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-lg text-[10px] sm:text-xs font-mono border border-blue-500/30 whitespace-nowrap">
                                {{ $laporan->nomor_ticket }}
                            </span>
                        </td>

                        <!-- Pelapor -->
                        <td class="py-2 px-2">
                            <div class="text-gray-300 text-[11px] sm:text-sm font-medium truncate max-w-[100px] sm:max-w-[120px]"
                                title="{{ $laporan->nama_pelapor }}">
                                {{ $laporan->nama_pelapor }}
                            </div>
                            <div class="text-[9px] sm:text-xs text-gray-400">{{ $laporan->no_handphone }}</div>
                        </td>

                        <!-- Kantor (Hidden di mobile, muncul di sm) -->
                        <td class="py-2 px-2 hidden sm:table-cell">
                            <span
                                class="text-gray-300 text-[11px] sm:text-sm truncate max-w-[100px] sm:max-w-[120px] block"
                                title="{{ $laporan->kantor->nama_cabang ?? 'N/A' }}">
                                {{ $laporan->kantor->nama_cabang ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Aplikasi (Hidden di mobile & tablet, muncul di md) -->
                        <td class="py-2 px-2 hidden md:table-cell">
                            <span class="text-gray-300 text-[11px] sm:text-sm truncate max-w-[100px] block"
                                title="{{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}">
                                {{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Produk (Hidden di mobile/tablet/desktop kecil, muncul di lg) -->
                        <td class="py-2 px-2 hidden lg:table-cell">
                            <div class="text-gray-300 text-[11px] sm:text-sm truncate max-w-[100px]"
                                title="{{ $laporan->produk->kode_produk ?? '' }}">
                                {{ $laporan->produk->kode_produk ?? '' }}
                            </div>
                            <div class="text-[9px] sm:text-xs text-gray-400 truncate max-w-[100px]"
                                title="{{ $laporan->produk->nama_produk ?? 'N/A' }}">
                                {{ $laporan->produk->nama_produk ?? 'N/A' }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-2 px-2">
                            @php
                                $statusColors = [
                                    'open' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'process' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'done' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'reject' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    'pending' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                                    'escalate' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                                ];
                                $statusIcons = [
                                    'open' => '🟡',
                                    'process' => '🔄',
                                    'done' => '✓',
                                    'reject' => '✗',
                                    'pending' => '⏳',
                                    'escalate' => '⬆️',
                                ];
                                $statusColor =
                                    $statusColors[$laporan->status] ??
                                    'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                $statusIcon = $statusIcons[$laporan->status] ?? '•';
                            @endphp
                            <span
                                class="{{ $statusColor }} px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-lg text-[8px] sm:text-[10px] font-medium inline-flex items-center gap-0.5 sm:gap-1 border whitespace-nowrap">
                                <span class="text-[8px] sm:text-[10px]">{{ $statusIcon }}</span>
                                <span
                                    class="uppercase text-[8px] sm:text-[10px]">{{ substr($laporan->status, 0, 4) }}</span>
                            </span>
                        </td>

                        <!-- Tanggal (Hidden di mobile/tablet/desktop kecil, muncul di lg) -->
                        <td class="py-2 px-2 hidden lg:table-cell">
                            <div class="text-gray-300 text-[10px] sm:text-xs whitespace-nowrap">
                                {{ $laporan->tanggal_laporan->format('d/m/Y') }}
                            </div>
                            <div class="text-[8px] text-gray-400">
                                {{ $laporan->tanggal_laporan->format('H:i') }}
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="py-2 px-2">
                            <div class="flex items-center gap-0.5 sm:gap-1">
                                <!-- Tombol Detail -->
                                @if (isset($permissions['can_show']) && $permissions['can_show'])
                                    <a href="{{ route('laporan.show', $laporan->id) }}"
                                        class="text-blue-400 hover:text-blue-300 transition-colors p-1 hover:bg-blue-400/10 rounded-lg group"
                                        title="Detail">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                @endif

                                <!-- Tombol Edit (Hanya muncul jika status 'done') -->
                                @if (isset($permissions['can_edit']) && $permissions['can_edit'] && $laporan->status == 'done')
                                    <a href="{{ route('laporan.edit', $laporan->id) }}"
                                        class="text-yellow-400 hover:text-yellow-300 transition-colors p-1 hover:bg-yellow-400/10 rounded-lg group"
                                        title="Edit">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </a>
                                @endif

                                <!-- Tombol Update Status -->
                                @if (isset($permissions['can_update_status']) && $permissions['can_update_status'])
                                    <button onclick="openStatusModal('{{ $laporan->id }}', '{{ $laporan->status }}')"
                                        class="text-purple-400 hover:text-purple-300 transition-colors p-1 hover:bg-purple-400/10 rounded-lg group"
                                        title="Update Status">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </button>
                                @endif

                                <!-- Tombol WhatsApp (hanya jika status done) -->
                                @if (isset($permissions['can_wa']) && $permissions['can_wa'] && $laporan->status == 'done')
                                    <button onclick="sendWhatsApp('{{ $laporan->id }}')"
                                        class="text-emerald-400 hover:text-emerald-300 transition-colors p-1 hover:bg-emerald-400/10 rounded-lg group"
                                        title="Kirim ke WhatsApp">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover:scale-110 transition-transform"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.473-.149-.673.149-.2.298-.767.967-.94 1.165-.173.198-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.673-1.62-.922-2.22-.242-.579-.487-.5-.673-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                            <path
                                                d="M12 0C5.373 0 0 5.373 0 12c0 2.125.554 4.118 1.525 5.85L.052 23.334c-.107.428.272.798.698.691l5.54-1.447C8.063 23.68 9.967 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.858 0-3.634-.505-5.155-1.382l-.371-.221-3.828 1 .991-3.764-.215-.395C2.597 15.796 2 13.939 2 12 2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z" />
                                        </svg>
                                    </button>
                                @endif

                                <!-- Tombol Delete -->
                                @if (isset($permissions['can_delete']) && $permissions['can_delete'])
                                    <button
                                        onclick="openDeleteModal('{{ $laporan->id }}', '{{ $laporan->nomor_ticket }}', '{{ $laporan->nama_pelapor }}')"
                                        class="text-red-400 hover:text-red-300 transition-colors p-1 hover:bg-red-400/10 rounded-lg group"
                                        title="Hapus">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center gap-2 sm:gap-3">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm sm:text-base font-medium text-gray-300">Tidak ada data laporan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination - Responsive -->
    @if (method_exists($laporans, 'links') && $laporans->hasPages())
        <div
            class="mt-3 sm:mt-4 flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-3 border-t border-white/10 pt-3 sm:pt-4">
            <!-- Info data -->
            <p class="text-gray-400 text-[9px] sm:text-xs order-2 sm:order-1">
                Menampilkan
                <span class="text-white font-medium">{{ $laporans->firstItem() ?? 0 }}</span>
                -
                <span class="text-white font-medium">{{ $laporans->lastItem() ?? 0 }}</span>
                dari
                <span class="text-white font-medium">{{ $laporans->total() }}</span>
                data
            </p>

            <!-- Pagination links -->
            <div class="flex items-center gap-0.5 sm:gap-1 order-1 sm:order-2">
                <!-- Previous button -->
                @if ($laporans->onFirstPage())
                    <span
                        class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-white/5 text-gray-500 rounded text-[9px] sm:text-xs cursor-not-allowed flex items-center gap-0.5 border border-white/10">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="hidden sm:inline">Prev</span>
                    </span>
                @else
                    <a href="{{ $laporans->previousPageUrl() }}"
                        class="pagination-link px-1.5 sm:px-2 py-0.5 sm:py-1 bg-white/5 text-gray-300 rounded hover:bg-white/10 transition-colors text-[9px] sm:text-xs flex items-center gap-0.5 border border-white/10 hover:border-blue-400/30">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="hidden sm:inline">Prev</span>
                    </a>
                @endif

                <!-- Page numbers (simplified for small screens) -->
                <div class="flex items-center gap-0.5 sm:gap-1">
                    @php
                        $totalPages = $laporans->lastPage();
                        $currentPage = $laporans->currentPage();

                        // Show limited pages on mobile
                        if ($totalPages <= 3) {
                            $start = 1;
                            $end = $totalPages;
                        } else {
                            if ($currentPage == 1) {
                                $start = 1;
                                $end = 3;
                            } elseif ($currentPage == $totalPages) {
                                $start = $totalPages - 2;
                                $end = $totalPages;
                            } else {
                                $start = $currentPage - 1;
                                $end = $currentPage + 1;
                            }
                        }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $laporans->url(1) }}"
                            class="pagination-link w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center bg-white/5 text-gray-300 rounded hover:bg-white/10 transition-colors text-[9px] sm:text-xs border border-white/10 hover:border-blue-400/30">
                            1
                        </a>
                        @if ($start > 2)
                            <span class="text-gray-500 text-[8px] sm:text-[10px] px-0.5">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $laporans->url($i) }}"
                            class="pagination-link w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center text-[9px] sm:text-xs rounded transition-all border
                                {{ $laporans->currentPage() == $i
                                    ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium shadow-md shadow-blue-500/25 border-transparent'
                                    : 'bg-white/5 text-gray-300 hover:bg-white/10 border-white/10 hover:border-blue-400/30' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($end < $totalPages)
                        @if ($end < $totalPages - 1)
                            <span class="text-gray-500 text-[8px] sm:text-[10px] px-0.5">...</span>
                        @endif
                        <a href="{{ $laporans->url($totalPages) }}"
                            class="pagination-link w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center bg-white/5 text-gray-300 rounded hover:bg-white/10 transition-colors text-[9px] sm:text-xs border border-white/10 hover:border-blue-400/30">
                            {{ $totalPages }}
                        </a>
                    @endif
                </div>

                <!-- Next button -->
                @if ($laporans->hasMorePages())
                    <a href="{{ $laporans->nextPageUrl() }}"
                        class="pagination-link px-1.5 sm:px-2 py-0.5 sm:py-1 bg-white/5 text-gray-300 rounded hover:bg-white/10 transition-colors text-[9px] sm:text-xs flex items-center gap-0.5 border border-white/10 hover:border-blue-400/30">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                @else
                    <span
                        class="px-1.5 sm:px-2 py-0.5 sm:py-1 bg-white/5 text-gray-500 rounded text-[9px] sm:text-xs cursor-not-allowed flex items-center gap-0.5 border border-white/10">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
