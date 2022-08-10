<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RolePermissionRequest;

interface RoleServiceInterface
{
    public function listRoles();

    public function getRole($id);

    public function createRole(CreateRoleRequest $request);

    public function updateRole(UpdateRoleRequest $request, $id);

    public function deleteRole($id);

    public function createRolePermission(RolePermissionRequest $request, $id);

    public function checkRolePermission(RolePermissionRequest $request, $id);

    public function removeRolePermission(RolePermissionRequest $request, $id);

    public function updateRolePermission(RolePermissionRequest $request, $id);
}
