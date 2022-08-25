<?php

namespace Encoda\Rbac\Services\Interfaces;

use Encoda\Rbac\Http\Requests\Role\RoleSyncPermissionsRequest;
use Encoda\Rbac\Http\Requests\RolePermission\RoleAddPermissionRequest;

interface RolePermissionServiceInterface
{

    public function listAssociatedPermissions( string $roleUid );
    public function roleAddPermission( RoleAddPermissionRequest $request, string $roleUid );
    public function roleRemovePermission( string $roleUid, string $permissionUid );
    public function roleSyncPermissions(RoleSyncPermissionsRequest $request, string $roleUid );
}
