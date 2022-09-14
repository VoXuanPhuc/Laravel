<?php

namespace Encoda\Activity\Http\Controllers\Admin;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;

class AdminActivityController extends Controller
{

    protected ActivityServiceInterface $activityService;

    public function __construct(ActivityServiceInterface $activityService)
    {
        $this->activityService = $activityService;
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @return mixed
     */
    public function index($organizationUid, $divisionUid, $businessUnitUid): mixed
    {
        return $this->activityService->listActivities($organizationUid, $divisionUid, $businessUnitUid);
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return mixed
     */
    public function detail($organizationUid, $divisionUid, $businessUnitUid, $uid): mixed
    {
        return $this->activityService->getActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);
    }
    
    /**
     * @param CreateActivityRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @return mixed
     */
    public function create(CreateActivityRequest $request, $organizationUid, $divisionUid, $businessUnitUid): mixed
    {
        return $this->activityService->createActivity($request, $organizationUid, $divisionUid, $businessUnitUid);
    }
    
    /**
     * @param UpdateActivityRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return mixed
     */
    public function update(UpdateActivityRequest $request, $organizationUid, $divisionUid, $businessUnitUid, $uid): mixed
    {
        return $this->activityService->updateActivity($request, $organizationUid, $divisionUid, $businessUnitUid, $uid);
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return mixed
     */
    public function delete($organizationUid, $divisionUid, $businessUnitUid, $uid): mixed
    {
        return $this->activityService->deleteActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);
    }
    
    /**
     * @param $organizationUid
     * @return mixed
     */
    public function getOrgActivities($organizationUid): mixed
    {
        return $this->activityService->getOrgActivities($organizationUid);
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @return mixed
     */
    public function getDivisionActivities($organizationUid, $divisionUid): mixed
    {
        return $this->activityService->getDivisionActivities($organizationUid, $divisionUid);
    }
}
