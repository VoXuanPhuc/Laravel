<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Utility;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class UtilityRepository extends Repository implements UtilityRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Utility::class;
    }

    /**
     * @param $uid
     * @param string[] $columns
     * @return mixed
     */
    public function findByUid($uid, $columns = ['*'])
    {
        return $this->findOneByField( 'uid', $uid, $columns );
    }
}
