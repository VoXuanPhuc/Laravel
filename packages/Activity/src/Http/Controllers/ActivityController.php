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
    
    /**
     * @return mixed
     */
    public function list(): mixed
    {
        return $this->activityService->listActivities();
    }
    
    /**
     * @param $uid
     * @return mixed
     */
    public function show($uid): mixed
    {
        return $this->activityService->getActivity($uid);
    }
    
    /**
     * @param CreateActivityRequest $request
     * @return mixed
     */
    public function store(CreateActivityRequest $request): mixed
    {
        return $this->activityService->createActivity($request);
    }
    
    /**
     * @param UpdateActivityRequest $request
     * @param $uid
     * @return mixed
     */
    public function update(UpdateActivityRequest $request, $uid): mixed
    {
        return $this->activityService->updateActivity($request, $uid);
    }
}
