<?php

namespace Encoda\Dependency\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Dependency\Models\DependencyDetail;
use Encoda\Dependency\Repositories\Interfaces\DependencyDetailRepositoryInterface;

class DependencyDetailRepository extends Repository implements DependencyDetailRepositoryInterface
{
    public function model(): string
    {
        return DependencyDetail::class;
    }
}
