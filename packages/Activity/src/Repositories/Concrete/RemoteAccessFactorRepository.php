<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class RemoteAccessFactorRepository extends Repository implements RemoteAccessFactorRepositoryInterface
{
    
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return RemoteAccessFactor::class;
    }
}
