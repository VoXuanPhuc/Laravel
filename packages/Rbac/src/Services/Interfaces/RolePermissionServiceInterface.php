<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Role\RoleSyncPermissionsRequest;
use Encoda\Rbac\Http\Requests\Role\RoleRemoveMultiplePermissionRequest;
use Laravel\Lumen\Http\Request;

interface RolePermissionServiceInterface
{

    public function listAssociatedPermissions( string $roleUid );
    public function roleAddPermission( Request $request, string $roleUid );
    public function roleRemovePermission( string $roleUid, string $permissionUid );
    public function roleSyncPermissions(RoleSyncPermissionsRequest $request, string $roleUid );
}
