<?php

namespace Encoda\Organization\Repositories\Interfaces;

use Prettus\Repository\Contracts\RepositoryInterface;

interface OrganizationRepositoryInterface extends RepositoryInterface
{
    public function findByUid( $uid, $column = ['*'] );
}
