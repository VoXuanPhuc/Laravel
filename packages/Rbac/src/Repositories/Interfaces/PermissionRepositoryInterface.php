<?php

namespace Encoda\Rbac\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * Find permission by UUID
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid($uid, $column = ['*'] );
}
