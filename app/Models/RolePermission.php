<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = [
        'role_id',
        'menu_name',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete',
        'can_export',
        'can_import',
        'can_wa',
        'can_show',
        'can_update_status'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
        'can_export' => 'boolean',
        'can_import' => 'boolean',
         'can_wa' => 'boolean',
        'can_show' => 'boolean',
        'can_update_status' => 'boolean'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
