<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Rbac\Http\Requests\Role\CreateRoleRequest;
use Encoda\Rbac\Http\Requests\Role\UpdateRoleRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RolePermissionRequest;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Encoda\Rbac\Services\Concrete\Cache\PermissionRegistrar;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{

    protected RoleRepositoryInterface $roleRepository;
    protected PermissionRegistrar $permissionRegistrar;

    /**
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRegistrar $permissionRegistrar
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRegistrar $permissionRegistrar,
    )
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRegistrar = $permissionRegistrar;
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
        $role = $this->roleRepository->create($request->all());

        // Clear cache
        $this->permissionRegistrar->forgetCachedPermissions();

        return $role;
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateRole(UpdateRoleRequest $request, $uid)
    {
        $role = $this->roleRepository->findByUid( $uid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        $role = $this->roleRepository->update( $request->all(), $role->id );

        // Clear cache
        $this->permissionRegistrar->forgetCachedPermissions();

        return $role;
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteRole($uid)
    {
        $role = $this->roleRepository->findByUid( $uid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        return $this->roleRepository->delete( $role->id );
    }

}
