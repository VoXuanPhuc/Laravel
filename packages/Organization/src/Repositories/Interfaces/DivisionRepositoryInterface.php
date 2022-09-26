<?php

namespace Encoda\Organization\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface DivisionRepositoryInterface extends RepositoryInterface
{

    public function findByUid( $uid, $column = ['*'] );
}
