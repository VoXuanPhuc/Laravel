<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveTolerablePeriodDisruptionRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\TolerablePeriodDisruptionRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityRemoteAccessServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityTolerablePeriodDisruptionServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityTolerablePeriodDisruptionService extends BaseActivityService implements ActivityTolerablePeriodDisruptionServiceInterface
{

    public function __construct(
        protected ActivityServiceInterface                     $activityService,
        protected ActivityRepositoryInterface                  $activityRepository,
        protected TolerablePeriodDisruptionRepositoryInterface $tolerablePeriodDisruptionRepository
    )
    {
    }

    /**
     * @throws ServerErrorException|NotFoundException
     */
    public function saveTolerablePeriodDisruptions(SaveTolerablePeriodDisruptionRequest $request, $activityUid)
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findByUid($activityUid);

        if (!$activity) {
            throw new NotFoundException(__('activity::app.activity.not_found'));
        }

        try {
            // Remote access factor
            $activity->tolerablePeriodDisruptions()->sync(
                $this->getTolerablePeriodDisruptions($request->tolerable_period_disruptions)
            );

        } catch (Throwable $e) {

            Log::error($e);
            throw new ServerErrorException(__('activity::app.activity.update_tolerable_period_disruption_error'));
        }

        return $activity;
    }


    /**
     * @param $rafs
     *
     * @return mixed
     */
    protected function getTolerablePeriodDisruptions($tpds)
    {
        $tpds = collect($tpds);
        return $tpds->mapWithKeys(function ($item) {
            $tdp = $this->tolerablePeriodDisruptionRepository->findByUid($item['uid']);
            return [$tdp->id => [
                "dependent_time"               => $item["dependent_time"],
                "reason_choose_dependent_time" => $item["reason_choose_dependent_time"],
            ]];
        })->toArray();
    }
}
