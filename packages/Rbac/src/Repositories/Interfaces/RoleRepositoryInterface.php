<?php

namespace Encoda\Rbac\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUid( $uid, $columns = ['*'] );
}
