<!-- Laporan Table -->
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">No. Ticket</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Pelapor</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Kantor</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aplikasi</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Produk</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Status</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Tanggal</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <td class="py-3 px-4">
                            <span
                                class="bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded-lg text-xs font-mono border border-blue-500/30">
                                {{ $laporan->nomor_ticket }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-300 text-sm font-medium">{{ $laporan->nama_pelapor }}</div>
                            <div class="text-xs text-gray-400">{{ $laporan->no_handphone }}</div>
                        </td>
                        <td class="py-3 px-4 text-gray-300 text-sm">{{ $laporan->kantor->nama_cabang ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-gray-300 text-sm">
                            {{ $laporan->jenisAplikasi->jenis_aplikasi ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-300 text-sm">{{ $laporan->produk->kode_produk ?? '' }}</div>
                            <div class="text-xs text-gray-400">{{ $laporan->produk->nama_produk ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3 px-4">
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
                                class="{{ $statusColor }} px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1 border">
                                <span>{{ $statusIcon }}</span>
                                <span class="uppercase">{{ $laporan->status }}</span>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-300 text-sm">
                                {{ $laporan->tanggal_laporan->format('d/m/Y H:i') }}
                            </div>
                            @if ($laporan->tanggal_selesai)
                                <div class="text-xs text-gray-400">Selesai:
                                    {{ $laporan->tanggal_selesai->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <!-- Tombol Detail (SHOW) -->
                                @if (isset($permissions['can_show']) && $permissions['can_show'])
                                    <a href="{{ route('laporan.show', $laporan->id) }}"
                                        class="text-blue-400 hover:text-blue-300 transition-colors p-2 hover:bg-blue-400/10 rounded-lg group"
                                        title="Detail">
                                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                @endif

                                <!-- Tombol Edit -->
                                @if (isset($permissions['can_edit']) && $permissions['can_edit'])
                                    <a href="{{ route('laporan.edit', $laporan->id) }}"
                                        class="text-yellow-400 hover:text-yellow-300 transition-colors p-2 hover:bg-yellow-400/10 rounded-lg group"
                                        title="Edit">
                                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
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
                                        class="text-purple-400 hover:text-purple-300 transition-colors p-2 hover:bg-purple-400/10 rounded-lg group"
                                        title="Update Status">
                                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </button>
                                @endif

                                <!-- TOMBOL WHATSAPP - hanya muncul jika status DONE -->
                                @if (isset($permissions['can_wa']) && $permissions['can_wa'] && $laporan->status == 'done')
                                    <button onclick="sendWhatsApp('{{ $laporan->id }}')"
                                        class="text-emerald-400 hover:text-emerald-300 transition-colors p-2 hover:bg-emerald-400/10 rounded-lg group"
                                        title="Kirim ke WhatsApp">
                                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
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
                                        class="text-red-400 hover:text-red-300 transition-colors p-2 hover:bg-red-400/10 rounded-lg group"
                                        title="Hapus">
                                        <svg class="w-4 h-4 transform group-hover:scale-110 transition-transform"
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
                        <td colspan="8" class="py-12 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data laporan</p>
                                <p class="text-xs text-gray-500">Klik tombol "Buat Laporan Baru" untuk menambahkan data
                                </p>
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
                @if ($laporans->onFirstPage())
                    <span
                        class="px-3 py-1.5 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed flex items-center gap-1 border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </span>
                @else
                    <a href="{{ $laporans->previousPageUrl() }}"
                        class="pagination-link px-3 py-1.5 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm flex items-center gap-1 border border-white/10 hover:border-blue-400/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                    </a>
                @endif

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
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm border border-white/10 hover:border-blue-400/30">
                            1
                        </a>
                        @if ($start > 2)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <a href="{{ $laporans->url($i) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center text-sm rounded-lg transition-all border
                                {{ $laporans->currentPage() == $i
                                    ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium shadow-lg shadow-blue-500/25 border-transparent'
                                    : 'bg-white/5 text-gray-300 hover:bg-white/10 border-white/10 hover:border-blue-400/30' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($end < $totalPages)
                        @if ($end < $totalPages - 1)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-500">...</span>
                        @endif
                        <a href="{{ $laporans->url($totalPages) }}"
                            class="pagination-link w-8 h-8 flex items-center justify-center bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm border border-white/10 hover:border-blue-400/30">
                            {{ $totalPages }}
                        </a>
                    @endif
                </div>

                @if ($laporans->hasMorePages())
                    <a href="{{ $laporans->nextPageUrl() }}"
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
