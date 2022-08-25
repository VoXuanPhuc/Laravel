<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Http\Requests\Role\RoleSyncPermissionsRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RoleAddPermissionRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RolePermissionRequest;
use Encoda\Rbac\Services\Interfaces\RolePermissionServiceInterface;
use Laravel\Lumen\Http\Request;

class RolePermissionController extends Controller
{

    public function __construct( protected RolePermissionServiceInterface $rolePermissionService )
    {
    }

    /**
     * Get list associated permissions to role
     * @param string $roleUid
     */
    public function roleListAssociatedPermissions( string $roleUid ) {

        $this->rolePermissionService->listAssociatedPermissions( $roleUid );
    }

    /**
     * @param RoleAddPermissionRequest $request
     * @param string $roleUid
     */
    public function roleAddPermission( RoleAddPermissionRequest $request, string $roleUid ) {

        return $this->rolePermissionService->roleAddPermission( $request, $roleUid );
    }

    /**
     * @param string $roleUid
     * @param string $permissionUid
     */
    public function roleRemovePermission( string $roleUid, string $permissionUid ) {
        return $this->rolePermissionService->roleRemovePermission( $roleUid, $permissionUid );
    }

    /**
     * @param RoleSyncPermissionsRequest $request
     * @param string $roleUid
     */
    public function roleSyncPermissions( RoleSyncPermissionsRequest $request, string $roleUid ) {
        $this->rolePermissionService->roleSyncPermissions( $request, $roleUid );
    }
}
