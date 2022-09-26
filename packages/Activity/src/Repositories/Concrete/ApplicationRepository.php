<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Application;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApplicationRepository extends Repository implements ApplicationRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Application::class;
    }

    public function findByUid( $uid, $columns = ['*'] ){
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
