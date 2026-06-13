{{-- resources/views/role_permissions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Role Permissions - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <div class="flex-1 p-6 overflow-y-auto">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Role Permissions</h1>
                    <p class="text-gray-400">Kelola hak akses setiap role</p>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-4 bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">ID</th>
                                <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Nama Role</th>
                                <th class="text-left text-gray-400 text-xs font-medium py-3 px-4">Aksi</th>
                            <tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                @php
                                    $totalPermissions = $role->permissions->sum(function ($perm) {
                                        return ($perm->can_view ? 1 : 0) +
                                            ($perm->can_create ? 1 : 0) +
                                            ($perm->can_edit ? 1 : 0) +
                                            ($perm->can_delete ? 1 : 0) +
                                            ($perm->can_export ? 1 : 0) +
                                            ($perm->can_excel ? 1 : 0) +
                                            ($perm->can_import ? 1 : 0);
                                    });
                                @endphp
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-4 text-gray-300 text-sm">{{ $role->id }}</td>
                                    <td class="py-3 px-4">
                                        <span class="text-white font-medium">{{ $role->nama_role }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('role-permissions.show', $role->id) }}"
                                                class="text-blue-400 hover:text-blue-300 transition-colors p-1 hover:bg-blue-400/10 rounded-lg"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 0112 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('role-permissions.edit', $role->id) }}"
                                                class="text-yellow-400 hover:text-yellow-300 transition-colors p-1 hover:bg-yellow-400/10 rounded-lg"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">
                                        Tidak ada data role
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if (method_exists($roles, 'links') && $roles->hasPages())
                    <div class="mt-4">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Matrix View Modal -->
    <div id="matrixModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-[#001D39] border border-white/10 rounded-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white">Permission Matrix</h3>
                <button onclick="closeMatrixModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" id="matrixContent" style="max-height: calc(90vh - 120px);">
                <!-- Matrix will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom scrollbar untuk konten */
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

            /* Modal animation */
            #matrixModal {
                transition: opacity 0.3s ease;
            }

            /* ============================================ */
            /* RESPONSIVE UNTUK MOBILE SAJA (max-width: 768px) */
            /* TIDAK MENGUBAH TAMPILAN PC/LAPTOP */
            /* ============================================ */
            @media (max-width: 768px) {

                /* Konten utama - kurangi padding */
                .flex-1.p-6 {
                    padding: 1rem !important;
                }

                /* Header - perkecil margin dan font */
                .mb-8 {
                    margin-bottom: 1rem !important;
                }

                .text-3xl {
                    font-size: 1.25rem !important;
                    line-height: 1.75rem !important;
                }

                .text-gray-400 {
                    font-size: 0.75rem !important;
                }

                /* Header flex jadi column */
                .mb-8.flex.justify-between.items-center {
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 0.5rem !important;
                }

                /* Card padding */
                .bg-white\/5.backdrop-blur-xl.border.border-white\/10.rounded-2xl.p-6 {
                    padding: 1rem !important;
                }

                /* Tabel - scroll horizontal */
                .overflow-x-auto {
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                }

                table {
                    min-width: 400px !important;
                }

                table td,
                table th {
                    font-size: 11px !important;
                    padding: 8px 6px !important;
                    white-space: nowrap !important;
                }

                /* Action buttons di tabel */
                .flex.items-center.gap-2 {
                    gap: 4px !important;
                }

                .flex.items-center.gap-2 a {
                    padding: 4px 6px !important;
                }

                .flex.items-center.gap-2 svg {
                    width: 14px !important;
                    height: 14px !important;
                }

                /* Pagination - perkecil */
                nav[role="navigation"] div,
                nav[role="navigation"] a,
                nav[role="navigation"] span {
                    font-size: 11px !important;
                    padding: 4px 8px !important;
                }

                /* Modal - full width di mobile */
                #matrixModal .max-w-6xl {
                    max-width: calc(100% - 2rem) !important;
                    margin: 1rem !important;
                }

                #matrixModal .p-6 {
                    padding: 1rem !important;
                }

                #matrixModal .text-xl {
                    font-size: 1rem !important;
                }

                #matrixModal .w-6.h-6 {
                    width: 1.25rem !important;
                    height: 1.25rem !important;
                }
            }

            /* Tablet (769px - 1024px) - penyesuaian ringan */
            @media (min-width: 769px) and (max-width: 1024px) {
                .flex-1.p-6 {
                    padding: 1.5rem !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function openMatrixView(roleId) {
                document.getElementById('matrixModal').classList.remove('hidden');
                document.getElementById('matrixModal').classList.add('flex');

                // Load matrix content via AJAX
                fetch(`/role-permissions/${roleId}/matrix`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('matrixContent').innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('matrixContent').innerHTML =
                            '<p class="text-red-400">Error loading matrix view</p>';
                    });
            }

            function closeMatrixModal() {
                document.getElementById('matrixModal').classList.add('hidden');
                document.getElementById('matrixModal').classList.remove('flex');
                document.getElementById('matrixContent').innerHTML = '';
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                const modal = document.getElementById('matrixModal');
                if (e.target === modal) {
                    closeMatrixModal();
                }
            });
        </script>
    @endpush
@endsection
