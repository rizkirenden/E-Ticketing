<!-- Tombol Toggle - Elegant & Futuristic (di samping logo saat terbuka, muncul sendiri saat tertutup) -->
<div id="sidebar"
    class="w-72 h-screen bg-gradient-to-b from-[#002b4e] to-[#001a33] backdrop-blur-xl border-r border-white/10 flex flex-col relative overflow-hidden fixed left-0 top-0 z-40 transition-all duration-300 ease-in-out">

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5 pointer-events-none">
        <div class="absolute top-0 -left-20 w-60 h-60 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl">
        </div>
        <div class="absolute bottom-0 -right-20 w-60 h-60 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl">
        </div>
    </div>

    <!-- Sidebar Content - Relative z-10 agar di atas background -->
    <div class="relative z-10 flex flex-col h-full">

        <!-- Logo Section dengan tombol di samping kanan -->
        <div class="p-6 border-b border-white/10 relative">
            <div class="flex items-center justify-center">
                <div class="relative flex-shrink-0">
                    <div class="absolute inset-0 bg-blue-400 rounded-lg filter blur-xl opacity-50 glow-effect"></div>
                    <div class="relative w-16 h-16 overflow-hidden rounded-xl">
                        <img src="{{ asset('assets/logo2.PNG') }}" alt="Logo"
                            class="w-full h-full object-contain brightness-0 invert"
                            onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xl\'>ET</span>'">
                    </div>
                </div>
            </div>

            <!-- Tombol Toggle di dalam sidebar (samping kanan logo) -->
            <button id="sidebarToggleInner"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-xl backdrop-blur-md bg-gradient-to-br from-blue-500/20 to-purple-500/20 border border-white/30 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 transition-all duration-500 cursor-pointer group overflow-hidden flex items-center justify-center">

                <!-- Overlay pelindung agar tombol tidak tertimpa -->
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-xl pointer-events-none">
                </div>

                <!-- Animated gradient background -->
                <div
                    class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-400/50 to-purple-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                </div>

                <!-- Neon glow ring -->
                <div
                    class="absolute inset-0 rounded-xl border border-blue-400/0 group-hover:border-blue-400/60 transition-all duration-300 scale-90 group-hover:scale-100">
                </div>

                <!-- Icon dengan efek - fixed di tengah -->
                <svg id="toggleIconInner"
                    class="w-4 h-4 text-white relative z-10 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12 drop-shadow-[0_0_2px_rgba(59,130,246,0.8)]"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>

                <!-- Pulse dot -->
                <div
                    class="absolute -top-1 -right-1 w-2 h-2 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 animate-pulse shadow-[0_0_6px_#3b82f6]">
                </div>
            </button>
        </div>

        <!-- Navigation Menu - Scrollable Area -->
        <div class="flex-1 overflow-y-auto px-4 py-2 sidebar-scrollbar">
            <nav class="space-y-1">
                @php
                    $currentRoute = request()->route() ? request()->route()->getName() : '';
                    $user = Auth::user();
                    $roleName = $user->role->nama_role ?? '';
                    $accessibleMenus = \App\Helpers\PermissionHelper::getAccessibleMenus();

                    // Cek apakah user adalah SUPERADMIN
                    $isSuperAdmin = $roleName === 'SUPERADMIN';

                    function isActive($routeName, $currentRoute)
                    {
                        return $currentRoute === $routeName;
                    }

                    function isActivePrefix($prefix, $currentRoute)
                    {
                        return strpos($currentRoute, $prefix) === 0;
                    }

                    // Fungsi khusus untuk mengecek active state dengan lebih spesifik
                    function isRouteActive($pattern, $currentRoute, $exclude = null)
                    {
                        $isMatch = strpos($currentRoute, $pattern) === 0;
                        if ($exclude && $isMatch) {
                            return strpos($currentRoute, $exclude) !== 0;
                        }
                        return $isMatch;
                    }
                @endphp

                <!-- Menu Section -->
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2 block">Main
                    Menu</span>

                <!-- Dashboard - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('dashboard', $accessibleMenus))
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActive('dashboard', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActive('dashboard', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActive('dashboard', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Dashboard</span>
                    </a>
                @endif

                <!-- Ticketing - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('laporan', $accessibleMenus))
                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('laporan', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('laporan', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('laporan', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Ticketing</span>

                        <!-- Badge untuk menunjukkan jumlah permission -->
                        @php
                            $laporanPermissions = \App\Helpers\PermissionHelper::getButtonPermissions('laporan');
                            $hasActions =
                                $laporanPermissions['can_create'] ||
                                $laporanPermissions['can_edit'] ||
                                $laporanPermissions['can_delete'];
                        @endphp
                        @if ($hasActions && !$isSuperAdmin)
                            <span
                                class="bg-blue-500/20 text-blue-400 text-[10px] px-1.5 py-0.5 rounded-full border border-blue-500/30">
                                +
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Kantor - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('kantor', $accessibleMenus))
                    <a href="{{ route('kantor.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('kantor', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('kantor', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('kantor', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Kantor</span>
                    </a>
                @endif

                <!-- Jenis Aplikasi - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('jenis-aplikasi', $accessibleMenus))
                    <a href="{{ route('jenis-aplikasi.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('jenis-aplikasi', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('jenis-aplikasi', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('jenis-aplikasi', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Jenis Aplikasi</span>
                    </a>
                @endif

                <!-- Produk - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('produk', $accessibleMenus))
                    <a href="{{ route('produk.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('produk', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('produk', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('produk', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Produk</span>

                        <!-- Badge untuk menunjukkan jumlah permission -->
                        @php
                            $produkPermissions = \App\Helpers\PermissionHelper::getButtonPermissions('produk');
                            $hasActions =
                                $produkPermissions['can_create'] ||
                                $produkPermissions['can_edit'] ||
                                $produkPermissions['can_delete'];
                        @endphp
                        @if ($hasActions && !$isSuperAdmin)
                            <span
                                class="bg-blue-500/20 text-blue-400 text-[10px] px-1.5 py-0.5 rounded-full border border-blue-500/30">
                                +
                            </span>
                        @endif
                    </a>
                @endif

                <!-- Separator untuk menu administration - tampil jika ada salah satu menu admin yang visible -->
                @if (
                    $isSuperAdmin ||
                        in_array('user', $accessibleMenus) ||
                        in_array('role', $accessibleMenus) ||
                        in_array('role-aplikasi', $accessibleMenus) ||
                        in_array('activity-logs', $accessibleMenus) ||
                        in_array('role-permissions', $accessibleMenus))
                    <div class="my-4 border-t border-white/10"></div>

                    <!-- Administration Menu -->
                    <span
                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 mb-2 block">Administration</span>
                @endif

                <!-- Users - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('user', $accessibleMenus))
                    <a href="{{ route('user.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('user', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('user', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator"></div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('user', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">User Management</span>
                    </a>
                @endif

                <!-- Roles - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('role', $accessibleMenus))
                    <a href="{{ route('role.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ isActivePrefix('role', $currentRoute) ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (isActivePrefix('role', $currentRoute))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator">
                            </div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ isActivePrefix('role', $currentRoute) ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Roles</span>
                    </a>
                @endif

                <!-- PERBAIKAN: Permission Role (Role Aplikasi) - dengan pengecualian untuk role-permissions -->
                @if ($isSuperAdmin || in_array('role-aplikasi', $accessibleMenus))
                    <a href="{{ route('role-aplikasi.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ request()->routeIs('role-aplikasi*') && !request()->routeIs('role-permissions*') ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (request()->routeIs('role-aplikasi*') && !request()->routeIs('role-permissions*'))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator">
                            </div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ request()->routeIs('role-aplikasi*') && !request()->routeIs('role-permissions*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Permission Role Apllication</span>
                    </a>
                @endif

                <!-- PERBAIKAN: Role Permissions - dengan pattern spesifik -->
                @if ($isSuperAdmin || in_array('role-permissions', $accessibleMenus))
                    <a href="{{ route('role-permissions.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ request()->routeIs('role-permissions*') ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (request()->routeIs('role-permissions*'))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator">
                            </div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ request()->routeIs('role-permissions*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Role Permissions</span>
                    </a>
                @endif

                <!-- Audit Log / Activity Logs - Tampil jika punya permission -->
                @if ($isSuperAdmin || in_array('activity-logs', $accessibleMenus))
                    <a href="{{ route('activity-logs.index') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all group relative {{ request()->routeIs('activity-logs*') ? 'bg-blue-600/20 text-white border-l-2 border-blue-400' : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        @if (request()->routeIs('activity-logs*'))
                            <div class="absolute left-0 w-1 h-8 bg-blue-400 rounded-r-full active-menu-indicator">
                            </div>
                        @else
                            <div
                                class="absolute left-0 w-1 h-0 bg-blue-400 group-hover:h-8 transition-all duration-300 rounded-r-full">
                            </div>
                        @endif
                        <svg class="w-5 h-5 {{ request()->routeIs('activity-logs*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-400' }} transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="flex-1 text-sm font-medium">Audit Log</span>
                    </a>
                @endif

                <!-- Menu Summary (untuk menunjukkan total permissions) - Opsional -->
                @if (!$isSuperAdmin && count($accessibleMenus) > 0)
                    <div class="mt-4 px-3 py-2 bg-white/5 rounded-lg border border-white/10">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-400">Menu Access:</span>
                            <span class="text-blue-400 font-medium">{{ count($accessibleMenus) }} menus</span>
                        </div>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach (array_slice($accessibleMenus, 0, 3) as $menu)
                                <span
                                    class="text-[8px] bg-blue-500/10 text-blue-400 px-1 py-0.5 rounded border border-blue-500/20">
                                    {{ ucfirst(str_replace('-', ' ', $menu)) }}
                                </span>
                            @endforeach
                            @if (count($accessibleMenus) > 3)
                                <span class="text-[8px] text-gray-500">+{{ count($accessibleMenus) - 3 }} more</span>
                            @endif
                        </div>
                    </div>
                @endif

            </nav>
        </div>

        <!-- User Info Section -->
        <div class="px-4 py-3 border-t border-white/10 bg-white/5">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ substr($user->nama ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ $user->nama ?? 'User' }}</p>
                    <p class="text-gray-400 text-xs truncate flex items-center gap-1">
                        <span
                            class="w-1.5 h-1.5 rounded-full {{ $isSuperAdmin ? 'bg-purple-400' : 'bg-green-400' }}"></span>
                        {{ $roleName }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="p-4 border-t border-white/10">
            <a href="#" id="logoutBtn"
                class="w-full flex items-center space-x-3 px-3 py-2.5 text-gray-300 hover:text-white hover:bg-red-500/10 rounded-lg transition-all group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-400 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="flex-1 text-sm font-medium text-left">
                    Logout
                </span>
            </a>

            <!-- Hidden Logout Form -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
                @csrf
            </form>

            <!-- Version Info -->
            <div class="mt-4 px-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600">Develop By IT DIGITAL</span>
                    <span class="text-gray-600">E-Ticketing System</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tombol Toggle Float (muncul saat sidebar tertutup) -->
<button id="sidebarToggleFloat"
    class="fixed top-6 left-3 z-50 w-8 h-8 rounded-xl backdrop-blur-md bg-gradient-to-br from-blue-500/20 to-purple-500/20 border border-white/30 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 transition-all duration-500 cursor-pointer group overflow-hidden hidden">

    <!-- Animated gradient background -->
    <div
        class="absolute inset-0 bg-gradient-to-r from-blue-500/0 via-blue-400/50 to-purple-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-full group-hover:translate-x-full transition-transform duration-700">
    </div>

    <!-- Neon glow ring -->
    <div
        class="absolute inset-0 rounded-xl border border-blue-400/0 group-hover:border-blue-400/60 transition-all duration-300 scale-90 group-hover:scale-100">
    </div>

    <!-- Icon dengan efek -->
    <svg id="toggleIconFloat"
        class="w-4 h-4 text-white relative z-10 mx-auto transition-all duration-300 group-hover:scale-110 group-hover:rotate-12 drop-shadow-[0_0_2px_rgba(59,130,246,0.8)]"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
    </svg>

    <!-- Pulse dot -->
    <div
        class="absolute -top-1 -right-1 w-2 h-2 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 animate-pulse shadow-[0_0_6px_#3b82f6]">
    </div>
</button>

<style>
    /* Custom Scrollbar untuk sidebar */
    .sidebar-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
    }

    .sidebar-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.3);
        border-radius: 10px;
    }

    .sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(59, 130, 246, 0.6);
    }

    /* Animations */
    @keyframes glow {

        0%,
        100% {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
        }

        50% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
        }
    }

    .glow-effect {
        animation: glow 3s infinite;
    }

    .active-menu-indicator {
        transition: all 0.3s ease;
    }

    /* Tooltip styling */
    .group:hover .group-hover\:block {
        display: block;
    }

    /* Sidebar Collapsed State - sidebar fully hidden */
    .sidebar-collapsed {
        width: 0px !important;
        overflow: hidden !important;
        opacity: 0;
        visibility: hidden;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin logout?')) {
                    this.submit();
                }
            });
        }
    });
</script>

<script>
    // Sidebar Toggle Functionality - dua tombol: satu di dalam sidebar (samping logo), satu float
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtnInner = document.getElementById('sidebarToggleInner');
        const toggleBtnFloat = document.getElementById('sidebarToggleFloat');
        const toggleIconInner = document.getElementById('toggleIconInner');
        const toggleIconFloat = document.getElementById('toggleIconFloat');

        // Cek localStorage untuk state sidebar
        let isSidebarOpen = localStorage.getItem('sidebarOpen');

        // Jika belum ada setelan, default ke terbuka
        if (isSidebarOpen === null) {
            isSidebarOpen = 'true';
        }

        // Fungsi untuk mengupdate sidebar state
        function setSidebarState(open) {
            if (open === false) {
                // SIDEBAR TERTUTUP
                sidebar.classList.add('sidebar-collapsed');
                // Sembunyikan tombol dalam, tampilkan tombol float
                if (toggleBtnInner) toggleBtnInner.style.display = 'none';
                if (toggleBtnFloat) toggleBtnFloat.classList.remove('hidden');
                // Update icon kedua tombol (panah kanan untuk membuka)
                if (toggleIconInner) toggleIconInner.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>';
                if (toggleIconFloat) toggleIconFloat.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>';
                localStorage.setItem('sidebarOpen', 'false');
            } else {
                // SIDEBAR TERBUKA
                sidebar.classList.remove('sidebar-collapsed');
                // Tampilkan tombol dalam, sembunyikan tombol float
                if (toggleBtnInner) toggleBtnInner.style.display = 'flex';
                if (toggleBtnFloat) toggleBtnFloat.classList.add('hidden');
                // Update icon kedua tombol (panah kiri untuk menutup)
                if (toggleIconInner) toggleIconInner.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>';
                if (toggleIconFloat) toggleIconFloat.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>';
                localStorage.setItem('sidebarOpen', 'true');
            }
        }

        // Set initial state
        if (isSidebarOpen === 'false') {
            setSidebarState(false);
        } else {
            setSidebarState(true);
        }

        // Event listener untuk kedua tombol
        if (toggleBtnInner) {
            toggleBtnInner.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isCurrentlyOpen = !sidebar.classList.contains('sidebar-collapsed');
                setSidebarState(!isCurrentlyOpen);
            });
        }

        if (toggleBtnFloat) {
            toggleBtnFloat.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const isCurrentlyOpen = !sidebar.classList.contains('sidebar-collapsed');
                setSidebarState(!isCurrentlyOpen);
            });
        }
    });
</script>

<script>
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Keluar dari Sistem?',
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;margin-top:10px">
                    <div style="
                        width:70px;
                        height:70px;
                        border-radius:50%;
                        background:linear-gradient(135deg,#3b82f6,#6366f1);
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:28px;
                        color:white;
                        margin-bottom:15px;
                        box-shadow:0 0 25px rgba(59,130,246,0.6);">
                        👤
                    </div>
                    <div style="color:#94a3b8;font-size:14px">
                        Anda akan keluar dari
                    </div>
                    <div style="font-weight:600;font-size:16px;margin-top:5px">
                        E-Ticketing System
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: "Logout",
            cancelButtonText: "Batal",
            confirmButtonColor: "#ef4444",
            cancelButtonColor: "#334155",
            background: "rgba(15,23,42,0.95)",
            color: "#fff",
            width: 420,
            backdrop: `
                rgba(0,0,0,0.8)
                blur(6px)
            `,
            showClass: {
                popup: "animate__animated animate__zoomIn animate__faster"
            },
            hideClass: {
                popup: "animate__animated animate__zoomOut animate__faster"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Menutup session anda',
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false,
                    background: "#0f172a",
                    color: "#fff"
                }).then(() => {
                    document.getElementById('logout-form').submit();
                });
            }
        });
    });
</script>
