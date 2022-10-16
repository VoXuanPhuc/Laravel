<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Utility;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UtilityRepository extends Repository implements UtilityRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Utility::class;
    }
}
