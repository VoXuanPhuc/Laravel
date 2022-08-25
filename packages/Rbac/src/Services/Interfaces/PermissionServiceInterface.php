<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Permission\CreatePermissionRequest;
use Encoda\Rbac\Http\Requests\Permission\UpdatePermissionRequest;

interface PermissionServiceInterface
{
    public function listPermissions();

    public function getPermission($uid);

    public function createPermission(CreatePermissionRequest $request);

    public function updatePermission(UpdatePermissionRequest $request, $uid);

    public function deletePermission($uid);
}
