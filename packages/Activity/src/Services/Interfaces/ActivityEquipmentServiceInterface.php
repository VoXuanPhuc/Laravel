<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;

interface ActivityEquipmentServiceInterface
{

    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $activityUid );
}
