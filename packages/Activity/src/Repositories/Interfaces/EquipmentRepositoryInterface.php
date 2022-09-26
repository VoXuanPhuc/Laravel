<?php

namespace Encoda\Activity\Repositories\Interfaces;

use Encoda\Identity\Repositories\Interfaces\BaseRepositoryInterface;

interface EquipmentRepositoryInterface extends BaseRepositoryInterface
{

    public function findByUid( $uid, $columns = ['*'] );

    public function findByUids( $uids = [], $columns = ['*'] );
}
