<!-- User Table -->
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Nama</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Jabatan</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Username</th>
                    <th class="text-left text-gray-400 text-xs font-medium py-3">Role</th>
                    @if ($permissions['can_edit'] || $permissions['can_delete'])
                        <th class="text-left text-gray-400 text-xs font-medium py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-3 text-gray-300 text-sm font-medium">{{ $user->nama }}</td>
                        <td class="py-3 text-gray-300 text-sm">{{ $user->jabatan }}</td>
                        <td class="py-3 text-gray-300 text-sm">
                            <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-lg text-xs font-medium">
                                {{ $user->username }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-300 text-sm">
                            <span class="bg-purple-500/20 text-purple-400 px-2 py-1 rounded-lg text-xs font-medium">
                                {{ $user->role->nama_role ?? 'N/A' }}
                            </span>
                        </td>
                        @if ($permissions['can_edit'] || $permissions['can_delete'])
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    @if ($permissions['can_edit'])
                                        <button
                                            onclick="openEditModal('{{ $user->id }}', '{{ $user->nama }}', '{{ $user->jabatan }}', '{{ $user->username }}', '{{ $user->role_id }}')"
                                            class="text-blue-400 hover:text-blue-300 transition-colors p-1 hover:bg-blue-400/10 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if ($permissions['can_delete'] && $user->id != auth()->id())
                                        <button onclick="openDeleteModal('{{ $user->id }}', '{{ $user->nama }}')"
                                            class="text-red-400 hover:text-red-300 transition-colors p-1 hover:bg-red-400/10 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @elseif($user->id == auth()->id())
                                        <span class="text-gray-500 p-1 cursor-not-allowed"
                                            title="Tidak dapat menghapus user sendiri">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                </path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 4 + ($permissions['can_edit'] || $permissions['can_delete'] ? 1 : 0) }}"
                            class="py-8 text-center text-gray-400 text-sm">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <p>Tidak ada data user</p>
                                @if ($permissions['can_create'])
                                    <p class="text-xs">Klik tombol "Tambah User Baru" untuk menambahkan data</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if (method_exists($users, 'links') && $users->lastPage() > 1)
        <div class="mt-4 flex items-center justify-between">
            <p class="text-gray-400 text-sm">
                Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari
                {{ $users->total() }}
                data
            </p>
            <div class="flex items-center gap-2">
                {{-- Previous Button --}}
                @if ($users->onFirstPage())
                    <span class="px-3 py-1 bg-white/5 text-gray-500 rounded-lg text-sm cursor-not-allowed">←</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}"
                        class="pagination-link px-3 py-1 bg-white/5 text-gray-300 rounded-lg hover:bg-white/10 transition-colors text-sm">
                        ←
                    </a>
                @endif

                {{-- Tentukan 3 halaman berurutan berdasarkan halaman aktif --}}
                @php
                    $totalPages = $users->lastPage();
                    $currentPage = $users->currentPage();

                    if ($totalPages <= 3) {
                        // Jika total halaman <= 3, tampilkan semua
                        $start = 1;
                        $end = $totalPages;
                    } else {
                        // Tentukan 3 halaman berurutan
                        if ($currentPage <= 2) {
                            // Jika di awal (halaman 1 atau 2), tampilkan 1,2,3
                            $start = 1;
                            $end = 3;
                        } elseif ($currentPage >= $totalPages - 1) {
                            // Jika di akhir, tampilkan 3 halaman terakhir
                            $start = $totalPages - 2;
                            $end = $totalPages;
                        } else {
                            // Jika di tengah, tampilkan current-1, current, current+1
                            $start = $currentPage - 1;
                            $end = $currentPage + 1;
                        }
                    }
                @endphp

                {{-- Tampilkan 3 halaman berurutan --}}
                @for ($i = $start; $i <= $end; $i++)
                    <a href="{{ $users->url($i) }}"
                        class="pagination-link px-3 py-1 {{ $users->currentPage() == $i ? 'bg-blue-500/20 text-blue-400' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} rounded-lg text-sm">
                        {{ $i }}
                    </a>
                @endfor

                {{-- Next Button --}}
                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}"
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
