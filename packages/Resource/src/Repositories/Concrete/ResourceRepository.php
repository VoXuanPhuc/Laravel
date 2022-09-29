<?php

namespace Encoda\Resource\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Resource\Models\Resource;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;

class ResourceRepository extends Repository implements  ResourceRepositoryInterface
{

    public function model()
    {
        return Resource::class;
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
