<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\TolerableTimePeriod;
use Encoda\Activity\Repositories\Interfaces\TolerableTimePeriodRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class TolerableTimeTimePeriodRepository extends Repository implements TolerableTimePeriodRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return TolerableTimePeriod::class;
    }
}
