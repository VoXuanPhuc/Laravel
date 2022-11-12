<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\DisruptionScenario;
use Encoda\Activity\Repositories\Interfaces\DisruptionScenarioRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

/**
 *
 */
class DisruptionScenarioRepository extends Repository implements DisruptionScenarioRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return DisruptionScenario::class;
    }
}
