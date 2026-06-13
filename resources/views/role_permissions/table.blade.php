@php
    $menus = [
        'dashboard' => 'Dashboard',
        'laporan' => 'Ticketing',
        'kantor' => 'Kantor',
        'jenis-aplikasi' => 'Jenis Aplikasi',
        'produk' => 'Produk',
        'user' => 'User Management',
        'role' => 'Role Management',
        'role-aplikasi' => 'Permission Role',
        'activity-logs' => 'Audit Log',
        'role-permissions' => 'Role Permissions',
    ];

    $actions = [
        'can_view' => 'View',
        'can_create' => 'Create',
        'can_edit' => 'Edit',
        'can_delete' => 'Delete',
        'can_export' => 'Export',
        'can_import' => 'Import',
        'can_wa' => 'WA',
        'can_excel' => 'Excel',
        'can_show' => 'Show',
        'can_update_status' => 'Update Status',
    ];

    $actionColors = [
        'can_view' => 'blue',
        'can_create' => 'green',
        'can_edit' => 'yellow',
        'can_delete' => 'red',
        'can_export' => 'purple',
        'can_import' => 'orange',
        'can_wa' => 'pink',
        'can_show' => 'indigo',
        'can_update_status' => 'teal',
    ];

    // Tentukan action yang tersedia per menu
    $availableActionsPerMenu = [
        'dashboard' => ['can_view'],
        'laporan' => [
            'can_view',
            'can_create',
            'can_edit',
            'can_delete',
            'can_export',
            'can_import',
            'can_wa',
            'can_excel',
            'can_show',
            'can_update_status',
        ],
        'kantor' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
        'jenis-aplikasi' => ['can_view', 'can_create', 'can_edit', 'can_delete'],
        'produk' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
        'user' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
        'role' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
        'role-aplikasi' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
        'activity-logs' => ['can_view', 'can_export'],
        'role-permissions' => ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_export', 'can_import'],
    ];
@endphp

<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <!-- Header Actions -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-white">Permission Matrix</h2>
        <div class="flex gap-2">
            <button onclick="setAllPermissions('all', true)"
                class="px-3 py-1.5 bg-green-600/20 text-green-400 rounded-lg text-xs hover:bg-green-600/30 transition-colors border border-green-500/30">
                Check All
            </button>
            <button onclick="setAllPermissions('all', false)"
                class="px-3 py-1.5 bg-red-600/20 text-red-400 rounded-lg text-xs hover:bg-red-600/30 transition-colors border border-red-500/30">
                Uncheck All
            </button>
        </div>
    </div>

    <div class="overflow-x-auto" style="max-height: 70vh; overflow-y: auto;">
        <table class="w-full min-w-[1400px]">
            <thead class="sticky top-0 bg-[#001D39] z-20">
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4 sticky-left z-30"
                        style="min-width: 200px;">
                        <div class="flex items-center gap-2">
                            <span>Menu / Actions</span>
                            <span class="text-[10px] text-gray-500">(Sticky Column)</span>
                        </div>
                    </th>
                    @foreach ($roles as $role)
                        <th
                            class="text-center text-gray-400 text-xs font-medium py-3 px-4 min-w-[220px] relative group">
                            <div class="flex flex-col items-center">
                                <span class="text-white font-semibold">{{ $role->nama_role }}</span>
                                <span class="text-[10px] text-gray-500">ID: {{ $role->id }}</span>

                                <!-- Quick Actions Dropdown -->
                                <div class="absolute top-full mt-1 hidden group-hover:block z-30">
                                    <div
                                        class="bg-[#0a2a44] border border-white/10 rounded-lg shadow-xl p-1 min-w-[120px]">
                                        <button onclick="setAllPermissions({{ $role->id }}, true)"
                                            class="w-full text-left px-2 py-1 text-xs text-green-400 hover:bg-white/5 rounded">
                                            Check All
                                        </button>
                                        <button onclick="setAllPermissions({{ $role->id }}, false)"
                                            class="w-full text-left px-2 py-1 text-xs text-red-400 hover:bg-white/5 rounded">
                                            Uncheck All
                                        </button>
                                        <div class="border-t border-white/10 my-1"></div>
                                        <button
                                            onclick="copyPermissions({{ $role->id }}, prompt('Masukkan ID Role tujuan:'))"
                                            class="w-full text-left px-2 py-1 text-xs text-blue-400 hover:bg-white/5 rounded">
                                            Copy to...
                                        </button>
                                        <button onclick="resetToDefault({{ $role->id }})"
                                            class="w-full text-left px-2 py-1 text-xs text-yellow-400 hover:bg-white/5 rounded">
                                            Reset Default
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </th>
                    @endforeach
                </tr>

                <!-- Action Headers -->
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="sticky-left bg-white/5"></th>
                    @foreach ($roles as $role)
                        <th class="text-center">
                            <div class="grid grid-cols-9 gap-1 px-2">
                                @foreach ($actions as $actionKey => $actionLabel)
                                    <span class="text-[8px] font-medium text-gray-500 uppercase tracking-wider"
                                        title="{{ $actionLabel }}">
                                        {{ substr($actionLabel, 0, 2) }}
                                    </span>
                                @endforeach
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach ($menus as $menuKey => $menuLabel)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                        <!-- Menu Name Column (Sticky) -->
                        <td class="py-2 px-4 sticky-left bg-[#001D39] group-hover:bg-[#0a2a44] transition-colors z-10">
                            <div class="flex items-center gap-2">
                                <span class="text-white font-medium text-sm">{{ $menuLabel }}</span>
                                <span class="text-[8px] text-gray-500">{{ $menuKey }}</span>

                                @if ($menuKey === 'dashboard')
                                    <span class="text-[8px] text-blue-400 bg-blue-500/10 px-1.5 py-0.5 rounded">View
                                        Only</span>
                                @elseif($menuKey === 'activity-logs')
                                    <span
                                        class="text-[8px] text-purple-400 bg-purple-500/10 px-1.5 py-0.5 rounded">View+Export</span>
                                @endif

                                <!-- Quick actions for this menu -->
                                <div class="hidden group-hover:flex items-center gap-1 ml-2">
                                    <button onclick="setMenuPermissions('{{ $menuKey }}', true)"
                                        class="text-green-400 hover:text-green-300" title="Check All">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                    <button onclick="setMenuPermissions('{{ $menuKey }}', false)"
                                        class="text-red-400 hover:text-red-300" title="Uncheck All">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>

                        <!-- Permissions Columns for each Role -->
                        @foreach ($roles as $role)
                            @php
                                $permission = $role->permissions->where('menu_name', $menuKey)->first();
                                $availableActions = $availableActionsPerMenu[$menuKey] ?? ['can_view'];
                            @endphp

                            <td class="py-2 px-2">
                                <div class="grid grid-cols-9 gap-1">
                                    @foreach ($actions as $actionKey => $actionLabel)
                                        @php
                                            $isAvailable = in_array($actionKey, $availableActions);
                                            $isChecked = $permission && $permission->$actionKey && $isAvailable;
                                            $color = $actionColors[$actionKey] ?? 'gray';
                                        @endphp

                                        @if ($isAvailable)
                                            <div class="tooltip flex justify-center">
                                                <input type="checkbox"
                                                    class="permission-checkbox w-4 h-4 rounded border-2 border-white/20 bg-white/5
                                                              checked:bg-{{ $color }}-500 checked:border-{{ $color }}-500
                                                              focus:ring-2 focus:ring-{{ $color }}-500/50 transition-all"
                                                    {{ $isChecked ? 'checked' : '' }} data-role="{{ $role->id }}"
                                                    data-menu="{{ $menuKey }}" data-action="{{ $actionKey }}"
                                                    onchange="updatePermission(this)"
                                                    {{ $role->nama_role === 'SUPERADMIN' ? 'disabled' : '' }}>
                                                <span class="tooltiptext">{{ $actionLabel }}</span>
                                            </div>
                                        @else
                                            <div class="flex justify-center opacity-20">
                                                <div class="w-4 h-4 rounded border-2 border-white/20 bg-white/5"></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>

            <!-- Summary Row -->
            <tfoot class="sticky bottom-0 bg-[#001D39] border-t border-white/10">
                <tr>
                    <td class="sticky-left bg-[#001D39] py-2 px-4">
                        <span class="text-xs text-gray-400">Total Permissions</span>
                    </td>
                    @foreach ($roles as $role)
                        <td class="py-2 px-4 text-center">
                            @php
                                $totalPermissions = $role->permissions->sum(function ($perm) use (
                                    $availableActionsPerMenu,
                                ) {
                                    $menu = $perm->menu_name;
                                    $availableActions = $availableActionsPerMenu[$menu] ?? ['can_view'];

                                    $total = 0;
                                    foreach ($availableActions as $action) {
                                        $permissionKey = 'can_' . $action;
                                        if (isset($perm->$permissionKey) && $perm->$permissionKey) {
                                            $total++;
                                        }
                                    }

                                    return $total;
                                });

                                $maxPermissions = array_reduce(
                                    array_keys($availableActionsPerMenu),
                                    function ($carry, $menu) use ($availableActionsPerMenu) {
                                        return $carry + count($availableActionsPerMenu[$menu]);
                                    },
                                    0,
                                );

                                $percentage =
                                    $maxPermissions > 0 ? round(($totalPermissions / $maxPermissions) * 100) : 0;
                            @endphp
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-xs text-white">{{ $totalPermissions }}/{{ $maxPermissions }}</span>
                                <div class="w-16 h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Legend -->
    <div class="mt-4 pt-3 border-t border-white/10 flex flex-wrap items-center gap-4 text-xs">
        <span class="text-gray-400">Legend:</span>
        @foreach ($actions as $actionKey => $actionLabel)
            <div class="flex items-center gap-1">
                <div
                    class="w-3 h-3 rounded border-2 border-{{ $actionColors[$actionKey] }}-500 bg-{{ $actionColors[$actionKey] }}-500/20">
                </div>
                <span class="text-gray-300">{{ $actionLabel }}</span>
            </div>
        @endforeach

        <div class="border-l border-white/10 h-4 mx-2"></div>

        <div class="flex items-center gap-1">
            <div class="w-3 h-3 rounded border-2 border-blue-500 bg-blue-500/20"></div>
            <span class="text-gray-300">Dashboard: View Only</span>
        </div>

        <div class="flex items-center gap-1">
            <div class="w-3 h-3 rounded border-2 border-purple-500 bg-purple-500/20"></div>
            <span class="text-gray-300">Activity Logs: View & Export</span>
        </div>

        <div class="flex items-center gap-1 ml-4">
            <span class="text-gray-400">Note:</span>
            <span class="text-gray-500 text-[10px]">SUPERADMIN has all permissions (disabled)</span>
        </div>
    </div>
</div>

<style>
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 70px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 2px 4px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -35px;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 9px;
        pointer-events: none;
        white-space: nowrap;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
