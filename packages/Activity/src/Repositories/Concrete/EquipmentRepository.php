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
}
