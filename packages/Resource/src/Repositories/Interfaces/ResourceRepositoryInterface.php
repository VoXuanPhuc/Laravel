<?php

namespace Encoda\Resource\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface ResourceRepositoryInterface extends RepositoryInterface
{

    /**
     * Find permission by UUID
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid( $uid, $column = ['*'] );
}