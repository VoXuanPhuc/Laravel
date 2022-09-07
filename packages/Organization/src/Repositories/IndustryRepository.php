<?php

namespace Encoda\Organization\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Industry;

class IndustryRepository extends Repository
{

    public function model()
    {
        return Industry::class;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function findByUid( $uid ) {
        return $this->findOneByField('uid', $uid);
    }
}
