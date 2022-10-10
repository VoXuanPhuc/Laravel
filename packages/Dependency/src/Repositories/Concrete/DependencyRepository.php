<?php

namespace Encoda\Dependency\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Dependency\Models\Dependency;
use Encoda\Dependency\Repositories\Interfaces\DependencyRepositoryInterface;

class DependencyRepository extends Repository implements DependencyRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Dependency::class;
    }
}
