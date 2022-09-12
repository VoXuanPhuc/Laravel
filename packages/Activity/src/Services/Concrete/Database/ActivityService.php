<?php

namespace Encoda\Activity\Services\Concrete\Database;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;

class ActivityService implements ActivityServiceInterface
{

    /**
     * @var ActivityRepositoryInterface
     */
    protected ActivityRepositoryInterface $activityRepository;

    /**
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * @return mixed
     */
    public function listActivities()
    {
        return $this->activityRepository->all();
    }

    /**
     * @param $uid
     * @return mixed
     */
   public function getActivity($uid): mixed
   {
        return $this->activityRepository->findByUid($uid);
    }

    /**
     * @param CreateActivityRequest $request
     * @return mixed
     */
    public function createActivity(CreateActivityRequest $request): mixed
    {
        return $this->activityRepository->create($request->all());
    }

    /**
     * @param UpdateActivityRequest $request
     * @param $uid
     * @return mixed
     */
    public function updateActivity(UpdateActivityRequest $request, $uid): mixed
    {
        return $this->activityRepository->update($request->all(), $uid);
    }

}
