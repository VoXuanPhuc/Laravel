<?php

namespace Encoda\Rbac\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Rbac\Models\PermissionGroup;
use Encoda\Rbac\Repositories\Interfaces\PermissionGroupRepositoryInterface;

class PermissionGroupRepository extends Repository implements PermissionGroupRepositoryInterface
{

    public function model()
    {
        return PermissionGroup::class;
    }
}
