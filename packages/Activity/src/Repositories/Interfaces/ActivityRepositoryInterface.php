<?php

namespace Encoda\Activity\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface ActivityRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUid( $uid, $columns = ['*'] );
}
