<?php

namespace Encoda\Organization\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface IndustryRepositoryInterface extends RepositoryInterface
{
    public function findByUid( $uid, $column = ['*'] );
    public function findByUids( $uids, $columns = ['*'] );
}
