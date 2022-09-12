<?php

namespace Encoda\Activity\Services\Interfaces;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;

interface ActivityServiceInterface
{
    public function listActivities();

    public function getActivity($uid);

    public function createActivity(CreateActivityRequest $request);

    public function updateActivity(UpdateActivityRequest $request, $uid);

    public function deleteActivity($uid);

}