<?php
// app/Traits/HasPermission.php

namespace App\Traits;

use App\Helpers\PermissionHelper;

trait HasPermission
{
    protected function checkPermission($menu, $action = 'view')
    {
        if (!PermissionHelper::can($menu, $action)) {
            if (request()->ajax() || request()->wantsJson()) {
                abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini');
            }

            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini');
        }
    }

    protected function authorizeMenu($menu)
    {
        $this->checkPermission($menu, 'view');
    }

    protected function authorizeCreate($menu)
    {
        $this->checkPermission($menu, 'create');
    }

    protected function authorizeEdit($menu)
    {
        $this->checkPermission($menu, 'edit');
    }

    protected function authorizeDelete($menu)
    {
        $this->checkPermission($menu, 'delete');
    }

    protected function authorizeExport($menu)
    {
        $this->checkPermission($menu, 'export');
    }

    protected function authorizeImport($menu)
    {
        $this->checkPermission($menu, 'import');
    }

    protected function authorizeWA($menu)
    {
        $this->checkPermission($menu, 'wa');
    }

    protected function authorizeShow($menu)
    {
        $this->checkPermission($menu, 'show');
    }

    protected function authorizeUpdateStatus($menu)
    {
        $this->checkPermission($menu, 'update_status');
    }

    protected function getButtonPermissions($menu)
    {
        return PermissionHelper::getButtonPermissions($menu);
    }
}
