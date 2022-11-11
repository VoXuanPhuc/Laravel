<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Models\TolerablePeriodDisruption;
use Encoda\Activity\Repositories\Interfaces\TolerablePeriodDisruptionRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class TolerablePeriodDisruptionRepository extends Repository implements TolerablePeriodDisruptionRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return TolerablePeriodDisruption::class;
    }
}
