<!-- Jenis Aplikasi Table -->
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Kode</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Jenis Aplikasi</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Deskripsi</th>
                    @if ($permissions['can_edit'] || $permissions['can_delete'])
                        <th class="text-left text-gray-400 text-xs font-medium py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($jenisAplikasis as $jenisAplikasi)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-3 text-gray-300 text-sm">
                            <span class="bg-purple-500/20 text-purple-400 px-2 py-1 rounded-lg text-xs font-medium">
                                {{ $jenisAplikasi->kode_jenis_aplikasi }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-300 text-sm">
                            <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-lg text-xs font-medium">
                                {{ $jenisAplikasi->jenis_aplikasi }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-300 text-sm">{{ $jenisAplikasi->deskripsi }}</td>
                        @if ($permissions['can_edit'] || $permissions['can_delete'])
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    @if ($permissions['can_edit'])
                                        <button
                                            onclick="openEditModal('{{ $jenisAplikasi->id }}', '{{ $jenisAplikasi->kode_jenis_aplikasi }}', '{{ $jenisAplikasi->jenis_aplikasi }}', '{{ $jenisAplikasi->deskripsi }}')"
                                            class="text-blue-400 hover:text-blue-300 transition-colors p-1 hover:bg-blue-400/10 rounded-lg"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if ($permissions['can_delete'])
                                        <button
                                            onclick="openDeleteModal('{{ $jenisAplikasi->id }}', '{{ $jenisAplikasi->jenis_aplikasi }}')"
                                            class="text-red-400 hover:text-red-300 transition-colors p-1 hover:bg-red-400/10 rounded-lg"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + ($permissions['can_edit'] || $permissions['can_delete'] ? 1 : 0) }}"
                            class="py-8 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <p>Tidak ada data jenis aplikasi</p>
                                @if ($permissions['can_create'])
                                    <p class="text-xs">Klik tombol "Tambah Jenis Aplikasi Baru" untuk menambahkan data
                                    </p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if (method_exists($jenisAplikasis, 'links') && $jenisAplikasis->lastPage() > 1)
        <div class="mt-4 flex items-center justify-between">
            <p class="text-gray-400 text-sm">
                Menampilkan {{ $jenisAplikasis->firstItem() ?? 0 }} - {{ $jenisAplikasis->lastItem() ?? 0 }} dari
                {{ $jenisAplikasis->total() }} data
            </p>
            <div class="flex items-center gap-2">
                {{-- Previous Button --}}
                @if ($jenisAplikasis->onFirstPage())
                    <span class="px-3 py-1 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed">←</span>
                @else
                    <a href="{{ $jenisAplikasis->previousPageUrl() }}"
                        class="pagination-link px-3 py-1 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm">
                        ←
                    </a>
                @endif

                {{-- Tentukan 3 halaman berurutan berdasarkan halaman aktif --}}
                @php
                    $totalPages = $jenisAplikasis->lastPage();
                    $currentPage = $jenisAplikasis->currentPage();

                    if ($totalPages <= 3) {
                        $start = 1;
                        $end = $totalPages;
                    } else {
                        if ($currentPage <= 2) {
                            $start = 1;
                            $end = 3;
                        } elseif ($currentPage >= $totalPages - 1) {
                            $start = $totalPages - 2;
                            $end = $totalPages;
                        } else {
                            $start = $currentPage - 1;
                            $end = $currentPage + 1;
                        }
                    }
                @endphp

                {{-- Tampilkan 3 halaman berurutan --}}
                @for ($i = $start; $i <= $end; $i++)
                    <a href="{{ $jenisAplikasis->url($i) }}"
                        class="pagination-link px-3 py-1 {{ $jenisAplikasis->currentPage() == $i ? 'bg-blue-500/20 text-blue-400' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} rounded-lg text-sm">
                        {{ $i }}
                    </a>
                @endfor

                {{-- Next Button --}}
                @if ($jenisAplikasis->hasMorePages())
                    <a href="{{ $jenisAplikasis->nextPageUrl() }}"
                        class="pagination-link px-3 py-1 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm">
                        →
                    </a>
                @else
                    <span class="px-3 py-1 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed">→</span>
                @endif
            </div>
        </div>
    @endif
</div>
