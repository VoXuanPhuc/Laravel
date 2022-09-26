<?php

namespace Encoda\Organization\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Repositories\Interfaces\IndustryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IndustryRepository extends Repository implements IndustryRepositoryInterface
{

    public function model()
    {
        return Industry::class;
    }

    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     */
    public function findByUid( $uid, $column = ['*'] ) {
        return $this->findOneByField( 'uid', $uid, $column );
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
