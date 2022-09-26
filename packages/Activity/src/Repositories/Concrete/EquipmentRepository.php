<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Equipment;
use Encoda\Activity\Repositories\Interfaces\EquipmentRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EquipmentRepository extends Repository implements EquipmentRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Equipment::class;
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
