<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class RolePermissionController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of roles with permissions
     */
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('role_permissions.index', compact('roles'));
    }

    /**
     * Show the form for creating new permissions
     */
    public function create()
    {
        $roles = Role::all();

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
            'can_show' => 'Show',
            'can_update_status' => 'Update Status',
        ];

        // Tentukan action yang tersedia per menu
        $menuActions = [
            'dashboard' => ['can_view'],
            'laporan' => [
                'can_view',
                'can_create',
                'can_edit',
                'can_delete',
                'can_export',
                'can_import',
                'can_wa',
                'can_show',
                'can_update_status'
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

        return view('role_permissions.create', compact('roles', 'menus', 'actions', 'menuActions'));
    }

    /**
     * Store newly created permissions
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'array'
        ]);

        try {
            $roleId = $request->role_id;

            // Hapus semua permission lama untuk role ini
            RolePermission::where('role_id', $roleId)->delete();

            // Simpan permission baru
            foreach ($request->permissions as $menuName => $actions) {
                // Untuk jenis-aplikasi, pastikan export, import, wa, show, update_status selalu false
                if ($menuName === 'jenis-aplikasi') {
                    RolePermission::create([
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => false,
                        'can_import' => false,
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ]);
                }
                // Untuk produk, batasi action yang tidak relevan
                elseif ($menuName === 'produk') {
                    RolePermission::create([
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => isset($actions['can_export']),
                        'can_import' => isset($actions['can_import']),
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ]);
                }
                else {
                    RolePermission::create([
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => isset($actions['can_export']),
                        'can_import' => isset($actions['can_import']),
                        'can_wa' => isset($actions['can_wa']),
                        'can_show' => isset($actions['can_show']),
                        'can_update_status' => isset($actions['can_update_status']),
                    ]);
                }
            }

            $role = Role::find($roleId);

            // Log activity
            $this->logCreate(
                'ROLE_PERMISSION',
                $roleId,
                "Menambahkan permission untuk role: {$role->nama_role}"
            );

            return redirect()->route('role-permissions.index')
                ->with('success', 'Permission berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified permissions
     */
    public function show($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

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

        return view('role_permissions.show', compact('role', 'menus'));
    }

    /**
     * Show the form for editing permissions
     */
    public function edit($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

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
            'can_show' => 'Show',
            'can_update_status' => 'Update Status',
        ];

        // Tentukan action yang tersedia per menu
        $menuActions = [
            'dashboard' => ['can_view'],
            'laporan' => [
                'can_view',
                'can_create',
                'can_edit',
                'can_delete',
                'can_export',
                'can_import',
                'can_wa',
                'can_show',
                'can_update_status'
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

        // Group permissions by menu
        $permissions = [];
        foreach ($role->permissions as $perm) {
            $permissions[$perm->menu_name] = [
                'can_view' => $perm->can_view,
                'can_create' => $perm->can_create,
                'can_edit' => $perm->can_edit,
                'can_delete' => $perm->can_delete,
                'can_export' => $perm->can_export,
                'can_import' => $perm->can_import,
                'can_wa' => $perm->can_wa,
                'can_show' => $perm->can_show,
                'can_update_status' => $perm->can_update_status,
            ];
        }

        return view('role_permissions.edit', compact('role', 'menus', 'actions', 'permissions', 'menuActions'));
    }

    /**
     * Update the specified permissions
     */
    public function update(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array'
        ]);

        try {
            $role = Role::findOrFail($roleId);

            // Ambil data lama untuk log
            $oldPermissions = RolePermission::where('role_id', $roleId)->get()->toArray();

            // Hapus semua permission lama
            RolePermission::where('role_id', $roleId)->delete();

            // Simpan permission baru dan kumpulkan data baru
            $newPermissions = [];
            foreach ($request->permissions as $menuName => $actions) {
                // Untuk jenis-aplikasi, batasi permissions
                if ($menuName === 'jenis-aplikasi') {
                    $permissionData = [
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => false,
                        'can_import' => false,
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ];
                }
                // Untuk produk, batasi action yang tidak relevan
                elseif ($menuName === 'produk') {
                    $permissionData = [
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => isset($actions['can_export']),
                        'can_import' => isset($actions['can_import']),
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ];
                }
                else {
                    $permissionData = [
                        'role_id' => $roleId,
                        'menu_name' => $menuName,
                        'can_view' => isset($actions['can_view']),
                        'can_create' => isset($actions['can_create']),
                        'can_edit' => isset($actions['can_edit']),
                        'can_delete' => isset($actions['can_delete']),
                        'can_export' => isset($actions['can_export']),
                        'can_import' => isset($actions['can_import']),
                        'can_wa' => isset($actions['can_wa']),
                        'can_show' => isset($actions['can_show']),
                        'can_update_status' => isset($actions['can_update_status']),
                    ];
                }

                RolePermission::create($permissionData);
                $newPermissions[] = $permissionData;
            }

            // Log activity
            $this->logUpdate(
                'ROLE_PERMISSION',
                $roleId,
                'UPDATE',
                "Mengupdate permission untuk role: {$role->nama_role}",
                [
                    'role_id' => $roleId,
                    'role_name' => $role->nama_role,
                    'permissions' => $oldPermissions
                ],
                [
                    'role_id' => $roleId,
                    'role_name' => $role->nama_role,
                    'permissions' => $newPermissions
                ]
            );

            return redirect()->route('role-permissions.index')
                ->with('success', 'Permission berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified permission
     */
    public function destroy($permissionId)
    {
        try {
            $permission = RolePermission::findOrFail($permissionId);
            $permission->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus permission'
            ], 500);
        }
    }

    /**
     * Bulk update permissions
     */
    public function updateBulk(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array'
        ]);

        try {
            $role = Role::findOrFail($roleId);

            // Ambil data lama untuk log
            $oldPermissions = RolePermission::where('role_id', $roleId)->get()->toArray();
            $newPermissions = [];

            foreach ($request->permissions as $menuName => $actions) {
                // Untuk jenis-aplikasi, batasi permissions
                if ($menuName === 'jenis-aplikasi') {
                    $permission = RolePermission::updateOrCreate(
                        ['role_id' => $roleId, 'menu_name' => $menuName],
                        [
                            'can_view' => isset($actions['can_view']),
                            'can_create' => isset($actions['can_create']),
                            'can_edit' => isset($actions['can_edit']),
                            'can_delete' => isset($actions['can_delete']),
                            'can_export' => false,
                            'can_import' => false,
                            'can_wa' => false,
                            'can_show' => false,
                            'can_update_status' => false,
                        ]
                    );
                }
                // Untuk produk
                elseif ($menuName === 'produk') {
                    $permission = RolePermission::updateOrCreate(
                        ['role_id' => $roleId, 'menu_name' => $menuName],
                        [
                            'can_view' => isset($actions['can_view']),
                            'can_create' => isset($actions['can_create']),
                            'can_edit' => isset($actions['can_edit']),
                            'can_delete' => isset($actions['can_delete']),
                            'can_export' => isset($actions['can_export']),
                            'can_import' => isset($actions['can_import']),
                            'can_wa' => false,
                            'can_show' => false,
                            'can_update_status' => false,
                        ]
                    );
                }
                else {
                    $permission = RolePermission::updateOrCreate(
                        ['role_id' => $roleId, 'menu_name' => $menuName],
                        [
                            'can_view' => isset($actions['can_view']),
                            'can_create' => isset($actions['can_create']),
                            'can_edit' => isset($actions['can_edit']),
                            'can_delete' => isset($actions['can_delete']),
                            'can_export' => isset($actions['can_export']),
                            'can_import' => isset($actions['can_import']),
                            'can_wa' => isset($actions['can_wa']),
                            'can_show' => isset($actions['can_show']),
                            'can_update_status' => isset($actions['can_update_status']),
                        ]
                    );
                }

                $newPermissions[] = $permission->toArray();
            }

            // Log activity untuk bulk update
            $this->logUpdate(
                'ROLE_PERMISSION',
                $roleId,
                'BULK_UPDATE',
                "Bulk update permission untuk role: {$role->nama_role}",
                ['permissions' => $oldPermissions],
                ['permissions' => $newPermissions]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Copy permissions from one role to another
     */
    public function copyPermissions(Request $request)
    {
        $request->validate([
            'from_role_id' => 'required|exists:roles,id',
            'to_role_id' => 'required|exists:roles,id'
        ]);

        try {
            $fromPermissions = RolePermission::where('role_id', $request->from_role_id)->get();
            $fromRole = Role::find($request->from_role_id);
            $toRole = Role::find($request->to_role_id);

            // Hapus permission tujuan
            RolePermission::where('role_id', $request->to_role_id)->delete();

            // Copy permissions
            $newPermissions = [];
            foreach ($fromPermissions as $perm) {
                // Untuk jenis-aplikasi, pastikan export, import, wa, show, update_status tetap false
                if ($perm->menu_name === 'jenis-aplikasi') {
                    $permissionData = [
                        'role_id' => $request->to_role_id,
                        'menu_name' => $perm->menu_name,
                        'can_view' => $perm->can_view,
                        'can_create' => $perm->can_create,
                        'can_edit' => $perm->can_edit,
                        'can_delete' => $perm->can_delete,
                        'can_export' => false,
                        'can_import' => false,
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ];
                }
                // Untuk produk
                elseif ($perm->menu_name === 'produk') {
                    $permissionData = [
                        'role_id' => $request->to_role_id,
                        'menu_name' => $perm->menu_name,
                        'can_view' => $perm->can_view,
                        'can_create' => $perm->can_create,
                        'can_edit' => $perm->can_edit,
                        'can_delete' => $perm->can_delete,
                        'can_export' => $perm->can_export,
                        'can_import' => $perm->can_import,
                        'can_wa' => false,
                        'can_show' => false,
                        'can_update_status' => false,
                    ];
                }
                else {
                    $permissionData = [
                        'role_id' => $request->to_role_id,
                        'menu_name' => $perm->menu_name,
                        'can_view' => $perm->can_view,
                        'can_create' => $perm->can_create,
                        'can_edit' => $perm->can_edit,
                        'can_delete' => $perm->can_delete,
                        'can_export' => $perm->can_export,
                        'can_import' => $perm->can_import,
                        'can_wa' => $perm->can_wa,
                        'can_show' => $perm->can_show,
                        'can_update_status' => $perm->can_update_status,
                    ];
                }

                RolePermission::create($permissionData);
                $newPermissions[] = $permissionData;
            }

            // Log activity
            $this->logCreate(
                'ROLE_PERMISSION',
                $request->to_role_id,
                "Copy permission dari role {$fromRole->nama_role} ke {$toRole->nama_role}"
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil disalin'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyalin permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset permissions to default for a role
     */
    public function resetToDefault($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);

            // Ambil data lama untuk log
            $oldPermissions = RolePermission::where('role_id', $roleId)->get()->toArray();

            // Hapus semua permissions
            RolePermission::where('role_id', $roleId)->delete();

            // Set default permissions berdasarkan role
            $defaultMenus = [
                'dashboard' => ['can_view' => true],
                'laporan' => [
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
                'kantor' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
                'jenis-aplikasi' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                ],
                'produk' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                ],
                'user' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
                'role' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
                'role-aplikasi' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
                'activity-logs' => [
                    'can_view' => true,
                    'can_export' => true,
                ],
                'role-permissions' => [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false,
                    'can_export' => true,
                    'can_import' => false,
                    'can_wa' => false,
                    'can_show' => true,
                    'can_update_status' => false,
                ],
            ];

            $newPermissions = [];
            foreach ($defaultMenus as $menuName => $actions) {
                $permissionData = [
                    'role_id' => $roleId,
                    'menu_name' => $menuName,
                    'can_view' => $actions['can_view'] ?? false,
                    'can_create' => $actions['can_create'] ?? false,
                    'can_edit' => $actions['can_edit'] ?? false,
                    'can_delete' => $actions['can_delete'] ?? false,
                    'can_export' => $actions['can_export'] ?? false,
                    'can_import' => $actions['can_import'] ?? false,
                    'can_wa' => $actions['can_wa'] ?? false,
                    'can_show' => $actions['can_show'] ?? false,
                    'can_update_status' => $actions['can_update_status'] ?? false,
                ];

                RolePermission::create($permissionData);
                $newPermissions[] = $permissionData;
            }

            // Log activity untuk reset
            $this->logUpdate(
                'ROLE_PERMISSION',
                $roleId,
                'RESET',
                "Reset permission ke default untuk role: {$role->nama_role}",
                ['permissions' => $oldPermissions],
                ['permissions' => $newPermissions]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Permission berhasil direset ke default'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mereset permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display matrix view for a specific role
     */
    public function matrix($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $allRoles = Role::with('permissions')->get();

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

        // Tentukan action yang tersedia per menu untuk matrix
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
                'can_show',
                'can_update_status'
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

        return view('role_permissions.matrix', compact('role', 'allRoles', 'menus', 'actions', 'actionColors', 'availableActionsPerMenu'));
    }

    /**
     * Export permissions to PDF
     */
    public function exportPdf(Request $request)
    {
        try {
            $roles = Role::with('permissions')->get();

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
                'can_show' => 'Show',
                'can_update_status' => 'Update Status',
            ];

            // Generate PDF logic here
            // You can use barryvdh/laravel-dompdf or similar package

            return response()->json([
                'status' => 'success',
                'message' => 'PDF export berhasil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal export PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export permissions to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $roles = Role::with('permissions')->get();

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
                'can_show' => 'Show',
                'can_update_status' => 'Update Status',
            ];

            // Generate Excel logic here
            // You can use maatwebsite/excel package

            return response()->json([
                'status' => 'success',
                'message' => 'Excel export berhasil'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal export Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}
