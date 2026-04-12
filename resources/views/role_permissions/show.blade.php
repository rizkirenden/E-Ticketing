{{-- resources/views/role_permissions/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Permission - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <!-- Konten dengan scroll - responsive padding -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto" style="height: 100vh;">
            <!-- Header dengan tombol - responsive -->
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Detail Permission</h1>
                    <p class="text-sm sm:text-base text-gray-400">Informasi lengkap hak akses role</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('role-permissions.edit', $role->id) }}"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-colors flex items-center gap-2 text-sm sm:text-base">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('role-permissions.index') }}"
                        class="px-3 sm:px-4 py-2 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors text-sm sm:text-base">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Role Info Card - responsive -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Role ID</label>
                        <p class="text-white font-mono text-sm sm:text-base break-all">{{ $role->id }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Nama Role</label>
                        <p class="text-white text-base sm:text-lg font-semibold break-words">{{ $role->nama_role }}</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Total Permissions</label>
                        @php
                            $totalPermissions = $role->permissions->sum(function ($perm) {
                                return ($perm->can_view ? 1 : 0) +
                                    ($perm->can_create ? 1 : 0) +
                                    ($perm->can_edit ? 1 : 0) +
                                    ($perm->can_delete ? 1 : 0) +
                                    ($perm->can_export ? 1 : 0) +
                                    ($perm->can_import ? 1 : 0) +
                                    ($perm->can_wa ? 1 : 0) +
                                    ($perm->can_show ? 1 : 0) +
                                    ($perm->can_update_status ? 1 : 0);
                            });

                            // Hitung max permissions berdasarkan menu
                            $menuMaxPermissions = [
                                'dashboard' => 1, // hanya view
                                'laporan' => 9, // semua aksi
                                'kantor' => 6,
                                'jenis-aplikasi' => 4, // view, create, edit, delete
                                'user' => 6,
                                'role' => 6,
                                'role-aplikasi' => 6,
                                'activity-logs' => 2, // view dan export
                                'role-permissions' => 6,
                            ];

                            $maxPermissions = array_sum($menuMaxPermissions);
                            $percentage = $maxPermissions > 0 ? round(($totalPermissions / $maxPermissions) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2 flex-wrap">
                            <span
                                class="text-white text-sm sm:text-base">{{ $totalPermissions }}/{{ $maxPermissions }}</span>
                            <div class="w-20 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-gray-400 text-xs">{{ $percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions List - responsive -->
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Daftar Hak Akses</h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
                    @forelse($role->permissions as $permission)
                        <div class="border border-white/10 rounded-lg overflow-hidden">
                            <div class="bg-white/5 px-3 sm:px-4 py-2 sm:py-3 border-b border-white/10">
                                <h3 class="text-white font-medium text-sm sm:text-base break-words">
                                    {{ $menus[$permission->menu_name] ?? $permission->menu_name }}
                                </h3>
                            </div>
                            <div class="p-3 sm:p-4">
                                @php
                                    $availableActions = [];
                                    if ($permission->menu_name === 'dashboard') {
                                        $availableActions = ['view'];
                                    } elseif ($permission->menu_name === 'activity-logs') {
                                        $availableActions = ['view', 'export'];
                                    } elseif ($permission->menu_name === 'laporan') {
                                        $availableActions = [
                                            'view',
                                            'create',
                                            'edit',
                                            'delete',
                                            'export',
                                            'import',
                                            'wa',
                                            'show',
                                            'update_status',
                                        ];
                                    } elseif ($permission->menu_name === 'jenis-aplikasi') {
                                        $availableActions = ['view', 'create', 'edit', 'delete'];
                                    } else {
                                        $availableActions = ['view', 'create', 'edit', 'delete', 'export', 'import'];
                                    }
                                @endphp

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 sm:gap-2">
                                    @foreach ($availableActions as $action)
                                        @php
                                            $permissionKey = 'can_' . $action;
                                            $hasPermission = $permission->$permissionKey;
                                        @endphp
                                        <div
                                            class="flex items-center gap-1.5 sm:gap-2 p-1.5 sm:p-2 rounded-lg {{ $hasPermission ? 'bg-green-500/10' : 'bg-red-500/10' }}">
                                            <div
                                                class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full {{ $hasPermission ? 'bg-green-400' : 'bg-red-400' }}">
                                            </div>
                                            <span
                                                class="text-[10px] sm:text-xs {{ $hasPermission ? 'text-green-400' : 'text-red-400' }} uppercase">
                                                {{ $action }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-400 text-sm sm:text-base">
                            Tidak ada permission untuk role ini
                        </div>
                    @endforelse
                </div>

                <!-- Summary - responsive -->
                <div class="mt-5 sm:mt-6 pt-3 sm:pt-4 border-t border-white/10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs sm:text-sm gap-2">
                        <span class="text-gray-400">Terakhir diupdate</span>
                        <span class="text-white">{{ $role->updated_at->format('d M Y H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom scrollbar - persis seperti dashboard */
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

        /* Ensure proper scrolling */
        .flex-1 {
            min-height: 0;
        }

        /* Break words for long text on mobile */
        .break-words {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .break-all {
            word-break: break-all;
        }

        /* Mobile touch improvements */
        @media (max-width: 640px) {
            .flex-1 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
@endpush
