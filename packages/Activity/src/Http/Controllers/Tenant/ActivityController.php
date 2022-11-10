<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRecoveryTimeAndDisruptionScenarioRequest;
use Encoda\Activity\Http\Requests\Activity\SaveDependenciesAndSuppliersRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;
use Encoda\Activity\Http\Requests\Activity\SaveTolerablePeriodDisruptionRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Services\Interfaces\ActivityDependencyServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityEquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityRemoteAccessServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityTolerablePeriodDisruptionServiceInterface;
use Encoda\Activity\Services\Interfaces\RecoveryTimeDisruptionScenarioServiceInterface;

class ActivityController extends Controller
{


    public function __construct(
        protected ActivityServiceInterface $activityService,
        protected ActivityRemoteAccessServiceInterface $remoteAccessService,
        protected ActivityEquipmentServiceInterface $equipmentService,
        protected ActivityTolerablePeriodDisruptionServiceInterface $tolerablePeriodDisruptionService,
        protected RecoveryTimeDisruptionScenarioServiceInterface $recoveryTimeDisruptionScenarioService,
        protected ActivityDependencyServiceInterface $activityDependencyService
    )
    {
    }


    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->activityService->listActivities();
    }
    /**
     * @return mixed
     */
    public function top(): mixed
    {
        return $this->activityService->top();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ): mixed
    {
        return $this->activityService->getActivity( $uid );
    }

    /**
     * @param CreateActivityRequest $request
     * @return Activity
     */
    public function create( CreateActivityRequest $request ): Activity
    {

        return $this->activityService->createActivity( $request );
    }

    /**
     * @param UpdateActivityRequest $request
     * @param $uid
     * @return Activity
     */
    public function update( UpdateActivityRequest $request, $uid ): Activity
    {

        return $this->activityService->updateActivity( $request, $uid );
    }


    public function delete( $uid ) {
        return $this->activityService->deleteActivity( $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function permanentDelete( $uid ): mixed
    {
        return $this->activityService->permanentDelete( $uid );
    }
    /**
     * @param SaveRemoteAccessRequest $request
     * @param $activityUid
     * @return Activity
     */
    public function saveRemoteAccessFactors( SaveRemoteAccessRequest $request, $activityUid ): Activity
    {

        return $this->remoteAccessService->saveRemoteAccessFactors( $request, $activityUid );
    }

    /**
     * @param SaveApplicationsAndEquipmentRequest $request
     * @param $activityUid
     * @return Activity
     */
    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $activityUid ): Activity
    {

        return $this->equipmentService->saveApplicationsAndEquipments( $request, $activityUid );
    }

    /**
     * @param SaveTolerablePeriodDisruptionRequest $request
     * @param $activityUid
     * @return Activity
     */
    public function saveTolerablePeriodDisruptions(SaveTolerablePeriodDisruptionRequest $request, $activityUid ): Activity
    {

        return $this->tolerablePeriodDisruptionService->saveTolerablePeriodDisruptions( $request, $activityUid );
    }

    /**
     * @param SaveRecoveryTimeAndDisruptionScenarioRequest $request
     * @param                                              $activityUid
     *
     * @return Activity
     */
    public function saveRecoveryTimeAndDisruptionScenario(SaveRecoveryTimeAndDisruptionScenarioRequest $request, $activityUid): Activity
    {

        return $this
            ->recoveryTimeDisruptionScenarioService
            ->saveRecoveryTimeAndDisruptionScenario($request, $activityUid);
    }

    public function saveDependenciesAndSuppliers(SaveDependenciesAndSuppliersRequest $request, $activityUid): Activity
    {
        return $this->activityDependencyService->saveDependenciesAndSuppliers($request, $activityUid);
    }


    /**
     * @param string $range
     * @return mixed
     */
    public function export( string $range = 'all' ): mixed
    {
        $response = $this->activityService->export( $this->getTenant(), $range );

        return $response->deleteFileAfterSend( false );
    }
}
