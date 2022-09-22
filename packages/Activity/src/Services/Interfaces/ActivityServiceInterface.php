<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;

interface ActivityServiceInterface
{
    public function listActivities($organizationUid );

    public function getActivity($organizationUid, $uid);

    public function createActivity(CreateActivityRequest $request, $organizationUid );

    public function updateActivity(UpdateActivityRequest $request, $organizationUid, $uid);

    public function deleteActivity($organizationUid, $uid);

    public function getOrgActivities($organizationUid);

    public function getDivisionActivities($organizationUid, $divisionUid);

    public function saveRemoteAccessFactors( SaveRemoteAccessRequest $request, $organizationUid, $activityUid );

    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $organizationUid, $activityUid );

    public function permanentDelete( $organizationUid, $uid );

    public function export( $organization, $divisionUid = '', $businessUnitUid = '', $range = 'all' );

}
