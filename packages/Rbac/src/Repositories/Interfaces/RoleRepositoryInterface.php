<?php

namespace Encoda\Rbac\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function checkRolePermission(array $attributes, $id);

    public function createRolePermission(array $attributes, $id);

    public function removeRolePermission(array $attributes, $id);

    public function updateRolePermission(array $attributes, $id);
}
