<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RemoteAccessFactorRepository extends Repository implements RemoteAccessFactorRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return RemoteAccessFactor::class;
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

    /**
     * @param array $uids
     * @param string[] $columns
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function findByUids($uids = [], $columns = ['*'])
    {
        return $this->findWhereIn( 'uid', $uids, $columns );
    }
}
