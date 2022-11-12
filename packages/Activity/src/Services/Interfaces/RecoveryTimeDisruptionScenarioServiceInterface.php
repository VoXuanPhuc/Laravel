<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\SaveRecoveryTimeAndDisruptionScenarioRequest;

interface RecoveryTimeDisruptionScenarioServiceInterface
{

    public function saveRecoveryTimeAndDisruptionScenario(SaveRecoveryTimeAndDisruptionScenarioRequest $request, $activityUid );
}
