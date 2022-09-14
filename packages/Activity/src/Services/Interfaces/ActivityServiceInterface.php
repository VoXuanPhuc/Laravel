<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;

interface ActivityServiceInterface
{
    public function listActivities($organizationUid, $divisionUid, $businessUnitUid);

    public function getActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);

    public function createActivity(CreateActivityRequest $request, $organizationUid, $divisionUid, $businessUnitUid);

    public function updateActivity(UpdateActivityRequest $request, $organizationUid, $divisionUid, $businessUnitUid, $uid);
    
    public function deleteActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);
    
    public function getOrgActivities($organizationUid);
    
    public function getDivisionActivities($organizationUid, $divisionUid);
    
}
