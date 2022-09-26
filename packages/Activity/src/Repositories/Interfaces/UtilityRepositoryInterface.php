<?php

namespace Encoda\Activity\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface UtilityRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUid( $uid, $columns = ['*'] );

    public function findByUids($uids = [], $columns = ['*'] );
}
