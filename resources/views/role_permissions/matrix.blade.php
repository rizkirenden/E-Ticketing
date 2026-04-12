{{-- resources/views/role_permissions/matrix.blade.php --}}
<div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6">
    <!-- Header -->
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-white">Permission Matrix - {{ $role->nama_role }}</h3>
            <p class="text-xs text-gray-400">ID: {{ $role->id }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="setAllPermissions({{ $role->id }}, true)"
                class="px-3 py-1.5 bg-green-600/20 text-green-400 rounded-lg text-xs hover:bg-green-600/30 transition-colors border border-green-500/30">
                Check All
            </button>
            <button onclick="setAllPermissions({{ $role->id }}, false)"
                class="px-3 py-1.5 bg-red-600/20 text-red-400 rounded-lg text-xs hover:bg-red-600/30 transition-colors border border-red-500/30">
                Uncheck All
            </button>
        </div>
    </div>

    <!-- Matrix Table -->
    <div class="overflow-x-auto" style="max-height: 60vh; overflow-y: auto;">
        <table class="w-full min-w-[800px]">
            <thead class="sticky top-0 bg-[#001D39] z-20">
                <tr class="border-b border-white/10">
                    <th class="text-left text-gray-400 text-xs font-medium py-3 px-4 sticky-left z-30"
                        style="min-width: 200px;">
                        <span>Menu / Actions</span>
                    </th>
                    <th class="text-center text-gray-400 text-xs font-medium py-3 px-4" colspan="6">
                        <span class="text-white font-semibold">{{ $role->nama_role }}</span>
                    </th>
                </tr>
                <tr class="border-b border-white/5 bg-white/5">
                    <th class="sticky-left bg-white/5"></th>
                    @foreach ($actions as $actionKey => $actionLabel)
                        <th class="text-center py-2 px-1">
                            <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wider"
                                title="{{ $actionLabel }}">
                                {{ substr($actionLabel, 0, 1) }}
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $menuKey => $menuLabel)
                    @php
                        $permission = $role->permissions->where('menu_name', $menuKey)->first();
                    @endphp
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-2 px-4 sticky-left bg-[#001D39] group-hover:bg-[#0a2a44] transition-colors">
                            <div class="flex items-center gap-2">
                                <span class="text-white text-sm">{{ $menuLabel }}</span>
                                <span class="text-[8px] text-gray-500">{{ $menuKey }}</span>
                            </div>
                        </td>
                        @foreach ($actions as $actionKey => $actionLabel)
                            @php
                                $isChecked = $permission && $permission->$actionKey;
                                $color = $actionColors[$actionKey] ?? 'gray';
                            @endphp
                            <td class="py-2 px-2 text-center">
                                <div class="tooltip inline-block">
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
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
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
    </div>
</div>

<style>
    /* Sticky column */
    .sticky-left {
        position: sticky;
        left: 0;
        background-color: #001D39;
        z-index: 10;
    }

    /* Checkbox styling */
    .permission-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .permission-checkbox:checked {
        background-color: currentColor;
    }

    .permission-checkbox:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Tooltip */
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 80px;
        background-color: #1e293b;
        color: #fff;
        text-align: center;
        border-radius: 4px;
        padding: 4px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -40px;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        pointer-events: none;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
