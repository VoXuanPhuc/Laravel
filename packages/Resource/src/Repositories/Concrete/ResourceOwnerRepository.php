<?php

namespace Encoda\Resource\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Resource\Models\ResourceOwner;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;

class ResourceOwnerRepository extends Repository implements ResourceOwnerRepositoryInterface
{

    public function model()
    {
        return ResourceOwner::class;
    }

    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid($uid, $column = ['*'])
    {
        return $this->findOneByField('uid', $uid );
    }
}
