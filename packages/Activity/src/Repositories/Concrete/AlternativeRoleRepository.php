<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Role;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AlternativeRoleRepository extends Repository implements AlternativeRoleRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Role::class;
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
