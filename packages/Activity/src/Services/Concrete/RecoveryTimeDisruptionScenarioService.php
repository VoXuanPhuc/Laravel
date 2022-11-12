<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\SaveRecoveryTimeAndDisruptionScenarioRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\DisruptionScenarioRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\RecoveryTimeRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Services\Interfaces\RecoveryTimeDisruptionScenarioServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecoveryTimeDisruptionScenarioService extends BaseActivityService implements RecoveryTimeDisruptionScenarioServiceInterface
{

    public function __construct(
        protected ActivityServiceInterface              $activityService,
        protected ActivityRepositoryInterface           $activityRepository,
        protected RecoveryTimeRepositoryInterface       $recoveryTimeRepository,
        protected DisruptionScenarioRepositoryInterface $disruptionScenarioRepository,
    )
    {
    }

    /**
     * @param SaveRecoveryTimeAndDisruptionScenarioRequest $request
     * @param                                              $activityUid
     *
     * @return Activity
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function saveRecoveryTimeAndDisruptionScenario(SaveRecoveryTimeAndDisruptionScenarioRequest $request, $activityUid): Activity
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findByUid($activityUid);

        if (!$activity) {
            throw new NotFoundException(__('activity::app.activity.not_found'));
        }

        try {

            // Applications
            $activity->recoveryTimes()->sync(
                $this->getRecoveryTimes($request->recovery_times)
            );

            // Equipments
            $activity->disruptionScenarios()->sync(
                $this->getDisruptionScenarios($request->disruption_scenarios)
            );


        } catch (Throwable $e) {

            Log::error($e);
            throw new ServerErrorException(__('activity::app.activity.update_remote_access_factor_error'));
        }

        return $activity->fresh()?->load('recoveryTimes', 'disruptionScenarios');
    }


    /**
     * @param $apps
     *
     * @return mixed
     */
    protected function getRecoveryTimes($recoveryTimes): mixed
    {
        $recoveryTimes = collect($recoveryTimes);
        return $recoveryTimes->mapWithKeys(function ($item) {
            $tdp = $this->recoveryTimeRepository->findByUid($item['uid']);
            return [$tdp->id => [
                "is_rto_tested" => $item["is_rto_tested"],
            ]];
        })->toArray();
    }


    /**
     * @param $equipments
     *
     * @return mixed
     */
    protected function getDisruptionScenarios($disruptionScenarios): mixed
    {
        $disruptionScenarios = collect($disruptionScenarios);
        return $disruptionScenarios->mapWithKeys(function ($item) {
            $tdp = $this->disruptionScenarioRepository->findByUid($item['uid']);
            return [$tdp->id => [
                "workaround_solution" => $item["workaround_solution"],
                "workaround_feasibly" => $item["workaround_feasibly"],
            ]];
        })->toArray();
    }

}
