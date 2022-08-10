<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Permission\CreatePermissionRequest;
use Encoda\Rbac\Http\Requests\Permission\UpdatePermissionRequest;

interface PermissionServiceInterface
{
    public function listPermissions();

    public function getPermission($id);

    public function createPermission(CreatePermissionRequest $request);

    public function updatePermission(UpdatePermissionRequest $request, $id);

    public function deletePermission($id);
}
