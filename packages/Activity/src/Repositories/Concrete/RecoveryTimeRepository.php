<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\RecoveryTime;
use Encoda\Activity\Repositories\Interfaces\RecoveryTimeRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

/**
 *
 */
class RecoveryTimeRepository extends Repository implements RecoveryTimeRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return RecoveryTime::class;
    }
}
