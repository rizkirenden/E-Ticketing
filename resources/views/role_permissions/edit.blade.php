{{-- resources/views/role_permissions/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Permission - E-Ticketing System')

@section('content')
    <div class="flex min-h-screen bg-[#001D39]">
        @include('layouts.sidebar')

        <!-- Konten halaman dengan scroll - responsive padding -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto" style="height: 100vh;">
            <!-- Header dengan tombol kembali - responsive -->
            <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Edit Permission</h1>
                    <p class="text-sm sm:text-base text-gray-400">Mengubah hak akses untuk role: <span
                            class="text-white font-semibold">{{ $role->nama_role }}</span></p>
                </div>
                <a href="{{ route('role-permissions.index') }}"
                    class="w-full sm:w-auto px-4 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Alert untuk SUPERADMIN - responsive -->
            @if ($role->nama_role === 'SUPERADMIN')
                <div class="mb-6 p-3 sm:p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg animate-pulse-slow">
                    <p class="text-yellow-400 flex items-center gap-2 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        SUPERADMIN memiliki akses penuh ke semua menu. Permission tidak dapat diubah.
                    </p>
                </div>
            @endif

            <!-- Tampilkan error validasi jika ada - responsive -->
            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center gap-2 text-red-400 mb-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold text-sm sm:text-base">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs sm:text-sm text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Utama -->
            <form action="{{ route('role-permissions.update', $role->id) }}" method="POST" id="permissionForm">
                @csrf
                @method('PUT')

                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl p-4 sm:p-6">
                    <!-- Info Role Card - responsive -->
                    <div
                        class="mb-6 p-3 sm:p-4 bg-gradient-to-r from-blue-500/10 to-purple-500/10 border border-blue-500/20 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <span class="text-xs text-gray-400">Role ID</span>
                                <p class="text-white font-mono text-base sm:text-lg">{{ $role->id }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400">Nama Role</span>
                                <p class="text-white font-semibold text-base sm:text-lg break-words">{{ $role->nama_role }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Settings -->
                    <div class="space-y-3 sm:space-y-4">
                        @foreach ($menus as $menuKey => $menuLabel)
                            <div
                                class="border border-white/10 rounded-lg overflow-hidden hover:border-blue-500/30 transition-colors duration-300">
                                <!-- Header Menu - responsive -->
                                <div
                                    class="bg-white/5 px-3 sm:px-4 py-2.5 sm:py-3 border-b border-white/10 flex justify-between items-center">
                                    <h3 class="text-white font-medium flex items-center gap-2 text-sm sm:text-base">
                                        @switch($menuKey)
                                            @case('dashboard')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                    </path>
                                                </svg>
                                            @break

                                            @case('activity-logs')
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                    </path>
                                                </svg>
                                            @break

                                            @default
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                                </svg>
                                        @endswitch
                                        {{ $menuLabel }}
                                    </h3>
                                </div>

                                <!-- Grid Actions - responsive grid -->
                                <div class="p-3 sm:p-4">
                                    <div
                                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-9 gap-3 sm:gap-4">
                                        @foreach ($actions as $actionKey => $actionLabel)
                                            @php
                                                $isAvailable = in_array(
                                                    $actionKey,
                                                    $menuActions[$menuKey] ?? ['can_view'],
                                                );
                                                $isChecked =
                                                    isset($permissions[$menuKey][$actionKey]) &&
                                                    $permissions[$menuKey][$actionKey] &&
                                                    $isAvailable;
                                            @endphp

                                            @if ($isAvailable)
                                                <label class="flex items-center gap-2 cursor-pointer group py-1">
                                                    <input type="checkbox"
                                                        name="permissions[{{ $menuKey }}][{{ $actionKey }}]"
                                                        value="1"
                                                        class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 bg-white/5 border-white/20 rounded focus:ring-blue-500 focus:ring-offset-0 transition-all duration-200 group-hover:border-blue-400"
                                                        {{ $isChecked ? 'checked' : '' }}
                                                        {{ $role->nama_role === 'SUPERADMIN' ? 'disabled' : '' }}>
                                                    <span
                                                        class="text-gray-300 text-xs sm:text-sm group-hover:text-white transition-colors duration-200 break-words">{{ $actionLabel }}</span>
                                                </label>
                                            @else
                                                <div class="flex items-center gap-2 opacity-30 cursor-not-allowed py-1">
                                                    <input type="checkbox" disabled
                                                        class="w-3.5 h-3.5 sm:w-4 sm:h-4 bg-white/5 border-white/20 rounded">
                                                    <span
                                                        class="text-gray-500 text-xs sm:text-sm break-words">{{ $actionLabel }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Buttons - responsive -->
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('role-permissions.index') }}"
                            class="order-2 sm:order-1 px-5 sm:px-6 py-2.5 border border-white/10 text-gray-300 rounded-lg hover:bg-white/5 transition-colors flex items-center justify-center gap-2 group text-sm sm:text-base">
                            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                            class="order-1 sm:order-2 px-5 sm:px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl hover:shadow-blue-600/50 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 text-sm sm:text-base"
                            {{ $role->nama_role === 'SUPERADMIN' ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:rotate-12 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update Permission
                        </button>
                    </div>
                </div>
            </form>
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

        /* Animasi untuk alert SUPERADMIN */
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }

        /* Styling untuk checkbox yang didisabled */
        input[type="checkbox"]:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Hover effect untuk card permission */
        .border-white\/10 {
            transition: all 0.3s ease;
        }

        .border-white\/10:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        }

        /* Styling untuk checkbox */
        input[type="checkbox"] {
            accent-color: #3b82f6;
            transition: all 0.2s ease;
        }

        input[type="checkbox"]:checked {
            transform: scale(1.05);
        }

        input[type="checkbox"]:hover:not(:disabled) {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Mobile touch improvements */
        @media (max-width: 640px) {
            label {
                min-height: 32px;
            }

            /* Make checkboxes easier to tap */
            input[type="checkbox"] {
                width: 18px !important;
                height: 18px !important;
                min-width: 18px;
                min-height: 18px;
            }

            /* Better touch feedback */
            label:active {
                opacity: 0.7;
            }

            button:active {
                transform: scale(0.98) !important;
            }
        }

        /* Break words for long labels */
        .break-words {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /* Ensure content doesn't overflow */
        .grid {
            overflow-x: visible;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Optional: Menambahkan konfirmasi sebelum submit (untuk perubahan besar)
        const form = document.getElementById('permissionForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                const totalChecked = checkboxes.length;

                // Jika total permission yang dipilih sangat sedikit, beri peringatan
                if (totalChecked < 3 && !confirm('Anda hanya memilih ' + totalChecked +
                        ' permission. Apakah Anda yakin?')) {
                    e.preventDefault();
                }
            });
        }

        // Optional: Menambahkan efek visual saat checkbox dipilih
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('label');
                if (label) {
                    if (this.checked) {
                        label.classList.add('text-blue-400');
                    } else {
                        label.classList.remove('text-blue-400');
                    }
                }
            });

            // Initialize label class
            const label = checkbox.closest('label');
            if (label && checkbox.checked) {
                label.classList.add('text-blue-400');
            }
        });
    </script>
@endpush
