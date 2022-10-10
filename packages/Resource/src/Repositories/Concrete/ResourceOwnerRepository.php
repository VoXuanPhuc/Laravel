<?php

namespace Encoda\Resource\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Resource\Models\ResourceOwner;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class ResourceOwnerRepository extends Repository implements ResourceOwnerRepositoryInterface
{

    public function model()
    {
        return ResourceOwner::class;
    }

    public function create(array $attributes)
    {

        Event::dispatch( 'resource.owner.create.before' );

        $owner = parent::create($attributes);

        Event::dispatch( 'resource.owner.create.after', $owner );

        return $owner;
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

    /**
     * @param $uids
     * @param string[] $columns
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function findByUids($uids, $columns = ['*'])
    {
        return $this->findWhereIn( 'uid', $uids, $columns );
    }
}
