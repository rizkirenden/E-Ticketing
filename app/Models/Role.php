<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nama_role'
    ];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Get the permissions for the role.
     */
    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    /**
     * Get permission for specific menu
     */
    public function getPermission($menu)
    {
        return $this->permissions()->where('menu_name', $menu)->first();
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($menu, $action = 'view')
    {
        $permission = $this->getPermission($menu);

        if (!$permission) {
            return false;
        }

        $actionMap = [
            'view' => 'can_view',
            'create' => 'can_create',
            'edit' => 'can_edit',
            'delete' => 'can_delete',
            'export' => 'can_export',
            'import' => 'can_import'
        ];

        $field = $actionMap[$action] ?? 'can_view';

        return $permission->$field ?? false;
    }

    /**
     * Get all permissions as array
     */
    public function getPermissionsArray()
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            $permissions[$permission->menu_name] = [
                'can_view' => $permission->can_view,
                'can_create' => $permission->can_create,
                'can_edit' => $permission->can_edit,
                'can_delete' => $permission->can_delete,
                'can_export' => $permission->can_export,
                'can_import' => $permission->can_import,
            ];
        }

        return $permissions;
    }

    /**
     * Sync permissions for this role
     */
    public function syncPermissions(array $permissions)
    {
        foreach ($permissions as $menuName => $actions) {
            RolePermission::updateOrCreate(
                ['role_id' => $this->id, 'menu_name' => $menuName],
                [
                    'can_view' => $actions['can_view'] ?? false,
                    'can_create' => $actions['can_create'] ?? false,
                    'can_edit' => $actions['can_edit'] ?? false,
                    'can_delete' => $actions['can_delete'] ?? false,
                    'can_export' => $actions['can_export'] ?? false,
                    'can_import' => $actions['can_import'] ?? false,
                ]
            );
        }
    }

    /**
     * Get accessible menus for this role
     */
    public function getAccessibleMenus()
    {
        if ($this->nama_role === 'SUPERADMIN') {
            return ['*']; // Semua menu
        }

        return $this->permissions()
            ->where('can_view', true)
            ->pluck('menu_name')
            ->toArray();
    }

    /**
     * Check if role is SUPERADMIN
     */
    public function isSuperAdmin()
    {
        return $this->nama_role === 'SUPERADMIN';
    }

    /**
     * Get menu statistics
     */
    public function getMenuStats()
    {
        $totalPermissions = $this->permissions->sum(function($perm) {
            return ($perm->can_view ? 1 : 0) +
                   ($perm->can_create ? 1 : 0) +
                   ($perm->can_edit ? 1 : 0) +
                   ($perm->can_delete ? 1 : 0) +
                   ($perm->can_export ? 1 : 0) +
                   ($perm->can_import ? 1 : 0);
        });

        return [
            'total_menus' => $this->permissions->count(),
            'total_permissions' => $totalPermissions,
            'menus_with_full_access' => $this->permissions
                ->filter(function($perm) {
                    return $perm->can_view &&
                           $perm->can_create &&
                           $perm->can_edit &&
                           $perm->can_delete;
                })
                ->count()
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // When role is deleted, delete all its permissions
        static::deleting(function($role) {
            $role->permissions()->delete();
        });

        // When role is created, create default permissions
        static::created(function($role) {
            if ($role->nama_role !== 'SUPERADMIN') {
                // Create default permissions for new role
                $defaultMenus = [
                    'dashboard', 'laporan', 'kantor', 'jenis-aplikasi',
                    'user', 'role', 'role-aplikasi', 'activity-logs', 'role-permissions'
                ];

                foreach ($defaultMenus as $menu) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'menu_name' => $menu,
                        'can_view' => false,
                        'can_create' => false,
                        'can_edit' => false,
                        'can_delete' => false,
                        'can_export' => false,
                        'can_import' => false
                    ]);
                }
            }
        });
    }
}
