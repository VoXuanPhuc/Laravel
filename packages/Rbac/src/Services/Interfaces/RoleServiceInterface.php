<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;

interface RoleServiceInterface
{
    public function listRoles();

    public function getRole($uid);

    public function createRole(CreateRoleRequest $request);

    public function updateRole(UpdateRoleRequest $request, $uid);

    public function deleteRole($uid);

}
