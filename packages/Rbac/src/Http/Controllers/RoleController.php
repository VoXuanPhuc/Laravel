<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RolePermissionRequest;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;

class RoleController extends Controller
{
    /**
     * @var RoleServiceInterface
     */
    protected RoleServiceInterface $roleService;

    /**
     * @param RoleServiceInterface $roleService
     */
    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @return mixed
     */
    public function list()
    {
        return $this->roleService->listRoles();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->roleService->getRole($id);
    }

    /**
     * @param CreateRoleRequest $request
     * @return mixed
     */
    public function create(CreateRoleRequest $request)
    {
        return $this->roleService->createRole($request);
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        return $this->roleService->updateRole($request, $id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->roleService->deleteRole($id);
    }

    /**
     * @param RolePermissionRequest $request
     * @param $id
     * @return mixed
     */
    public function createRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleService->createRolePermission($request, $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @param $id
     * @return mixed
     */
    public function checkRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleService->checkRolePermission($request, $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @param $id
     * @return mixed
     */
    public function removeRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleService->removeRolePermission($request, $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @param $id
     * @return mixed
     */
    public function updateRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleService->updateRolePermission($request, $id);
    }
}
