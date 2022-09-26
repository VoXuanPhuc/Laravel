<?php

namespace Encoda\Organization\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface BusinessUnitRepositoryInterface extends RepositoryInterface
{
    public function findByUid( $uid, $column = ['*'] );
}
