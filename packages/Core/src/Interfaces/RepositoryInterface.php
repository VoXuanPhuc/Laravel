<?php

namespace Encoda\Core\Interfaces;

interface RepositoryInterface extends \Prettus\Repository\Contracts\RepositoryInterface
{

    /**
     * Find by UUID
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid( $uid, $column = ['*'] );

    /**
     * @param $uids
     * @param string[] $columns
     * @return mixed
     */
    public function findByUids( $uids, $columns = ['*'] );
}
