<?php

namespace Encoda\Activity\Http\Controllers;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;

class ActivityController extends Controller
{

    protected ActivityServiceInterface $activityService;

    public function __construct(ActivityServiceInterface $activityService)
    {
        $this->activityService = $activityService;
    }

    public function list()
    {
        return $this->activityService->listActivities();
    }

    public function detail($uid)
    {
        return $this->activityService->getActivity($uid);
    }

    public function store(CreateActivityRequest $request)
    {
        return $this->activityService->createActivity($request);
    }

    public function update(UpdateActivityRequest $request, $uid)
    {
        return $this->activityService->updateActivity($request, $uid);
    }

    public function delete($uid)
    {
        return $this->activityService->deleteActivity($uid);
    }

}
