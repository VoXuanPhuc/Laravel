<?php

namespace Encoda\Dependency\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Dependency\Models\DependencyScenario;
use Encoda\Dependency\Repositories\Interfaces\DependencyScenarioRepositoryInterface;

class DependencyScenarioRepository extends Repository implements DependencyScenarioRepositoryInterface
{
    public function model()
    {
        return DependencyScenario::class;
    }
}
