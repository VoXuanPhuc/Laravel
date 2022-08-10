<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RolePermissionRequest;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{

    protected RoleRepositoryInterface $roleRepository;

    /**
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct( RoleRepositoryInterface $roleRepository )
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return mixed
     */
    public function listRoles()
    {
        return $this->roleRepository->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRole($id)
    {
        return $this->roleRepository->find($id);
    }

    /**
     * @param CreateRoleRequest $request
     * @return mixed
     */
    public function createRole(CreateRoleRequest $request)
    {
        return $this->roleRepository->create($request->all());
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $id
     * @return mixed
     */
    public function updateRole(UpdateRoleRequest $request, $id)
    {
        return $this->roleRepository->update($request->all(), $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }

    /**
     * @param RolePermissionRequest $request
     * @return mixed
     */
    public function createRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleRepository->createRolePermission($request->all(), $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @return mixed
     */
    public function checkRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleRepository->checkRolePermission($request->all(), $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @return void
     */
    public function removeRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleRepository->removeRolePermission($request->all(), $id);
    }

    /**
     * @param RolePermissionRequest $request
     * @return mixed
     */
    public function updateRolePermission(RolePermissionRequest $request, $id)
    {
        return $this->roleRepository->updateRolePermission($request->all(), $id);
    }
}
