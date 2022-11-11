<?php

namespace Encoda\Activity\Services\Interfaces;


use Encoda\Activity\Http\Requests\Activity\SaveTolerablePeriodDisruptionRequest;

interface ActivityTolerablePeriodDisruptionServiceInterface
{
    public function saveTolerablePeriodDisruptions(SaveTolerablePeriodDisruptionRequest $request, $activityUid );
}
