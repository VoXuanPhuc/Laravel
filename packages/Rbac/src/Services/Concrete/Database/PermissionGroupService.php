<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\Rbac\Models\PermissionGroup;
use Encoda\Rbac\Repositories\Interfaces\PermissionGroupRepositoryInterface;
use Encoda\Rbac\Services\Interfaces\PermissionGroupServiceInterface;

class PermissionGroupService implements PermissionGroupServiceInterface
{

    public function __construct( protected PermissionGroupRepositoryInterface $permissionGroupRepository )
    {
    }

    public function listPermissionGroup()
    {
        return $this->permissionGroupRepository->all();
    }

    public function listPermissionByGroup()
    {
        return PermissionGroup::with('permissions')->get();

    }
}
