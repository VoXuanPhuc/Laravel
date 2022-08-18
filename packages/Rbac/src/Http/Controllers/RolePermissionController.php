<?php

namespace Encoda\Rbac\Http\Controllers;

use Encoda\Rbac\Http\Requests\Role\RoleSyncPermissionsRequest;
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
     * @param Request $request
     * @param string $roleUid
     */
    public function roleAddPermission( Request $request, string $roleUid ) {

        $this->rolePermissionService->roleAddPermission( $request, $roleUid );
    }

    /**
     * @param RoleSyncPermissionsRequest $request
     * @param string $roleUid
     */
    public function roleSyncPermissions( RoleSyncPermissionsRequest $request, string $roleUid ) {
        $this->rolePermissionService->roleSyncPermissions( $request, $roleUid );
    }
}
