<?php

namespace Encoda\Activity\Repositories\Interfaces;

interface AlternativeRoleRepositoryInterface
{

    public function findByUids( $uids = [], $columns = ['*'] );
}
