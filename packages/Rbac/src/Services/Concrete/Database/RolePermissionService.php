<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Rbac\Http\Requests\Role\RoleSyncPermissionsRequest;
use Encoda\Rbac\Models\Role;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Encoda\Rbac\Services\Interfaces\RolePermissionServiceInterface;
use Laravel\Lumen\Http\Request;

class RolePermissionService implements RolePermissionServiceInterface
{

    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected PermissionRepositoryInterface $permissionRepository,
    )
    {

    }

    /**
     * @param string $roleUid
     * @return mixed
     * @throws NotFoundException
     */
    public function listAssociatedPermissions(string $roleUid)
    {
        $role = $this->roleRepository->findByUid( $roleUid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        return $role->permissions;
    }

    /**
     * @param Request $request
     * @param string $roleUid
     * @return Role
     * @throws NotFoundException
     */
    public function roleAddPermission(Request $request, string $roleUid)
    {
        /** @var Role $role */
        $role = $this->roleRepository->findByUid( $roleUid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        // Get permission
        $permission = $this->permissionRepository->findByUid( $request->permissionUid );

        if( !$permission ) {
            throw new NotFoundException(__('rbac::app.role.permission_not_found'));
        }

        if( !$role->hasPermissionTo( $permission ) ) {

            $role->givePermissionTo( $permission );
            $role->save();
        }


        return $role;
    }

    /**
     * @param string $roleUid
     * @param string $permissionUid
     * @return Role
     * @throws NotFoundException
     */
    public function roleRemovePermission(string $roleUid, string $permissionUid )
    {
        /** @var Role $role */
        $role = $this->roleRepository->findByUid( $roleUid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        // Get permission
        $permission = $this->permissionRepository->findByUid( $permissionUid );

        if( !$permission ) {
            throw new NotFoundException(__('rbac::app.role.permission_not_found'));
        }

        if( !$role->hasPermissionTo( $permission ) ) {
            throw new NotFoundException(__('rbac::app.role.permission_not_associated'));
        }

        $role->revokePermissionTo( $permission );

        $role->save();

        return $role;
    }

    /**
     * @param RoleSyncPermissionsRequest $request
     * @param string $roleUid
     * @return Role
     * @throws NotFoundException
     */
    public function roleSyncPermissions(RoleSyncPermissionsRequest $request, string $roleUid)
    {
        /** @var Role $role */
        $role = $this->roleRepository->findByUid( $roleUid );

        if( !$role ) {
            throw new NotFoundException(__('rbac::app.role.role_not_found'));
        }

        $permissions = [];

        foreach ( $request->get('permissionUids') as $permissionUid ) {
            if( $permission = $this->permissionRepository->findByUid( $permissionUid ) ) {
                array_push( $permissions, $permission );
            }
            else {
                throw new NotFoundException(__('rbac::app.role.permission_not_found'));
            }
        }

        // All permissions found
        $role->syncPermissions( $permissions );
        $role->save();

        return $role;
    }

}
