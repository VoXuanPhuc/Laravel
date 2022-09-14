<?php

namespace Encoda\Activity\Repositories\Concrete\Database;

use Encoda\Activity\Models\Utility;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

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
