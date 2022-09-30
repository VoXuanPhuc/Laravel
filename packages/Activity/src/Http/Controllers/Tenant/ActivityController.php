<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;

class ActivityController extends Controller
{


    public function __construct( protected ActivityServiceInterface $activityService )
    {
    }


    /**
     * @return mixed
     */
    public function index() {
        return $this->activityService->listActivities( $this->getTenant()->uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ) {
        return $this->activityService->getActivity( $this->getTenant()->uid, $uid );
    }

    /**
     * @param CreateActivityRequest $request
     * @return mixed
     */
    public function create( CreateActivityRequest $request ) {

        return $this->activityService->createActivity( $request, $this->getTenant()->uid );
    }

    /**
     * @param UpdateActivityRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateActivityRequest $request, $uid ) {

        return $this->activityService->updateActivity( $request, $this->getTenant()->uid, $uid );
    }


    public function delete( $uid ) {
        return [];
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function permanentDelete( $uid ) {
        return $this->activityService->permanentDelete( $this->getTenant()->uid, $uid );
    }
    /**
     * @param SaveRemoteAccessRequest $request
     * @param $activityUid
     * @return mixed
     */
    public function saveRemoteAccessFactors( SaveRemoteAccessRequest $request, $activityUid ) {

        return $this->activityService->saveRemoteAccessFactors( $request, $this->getTenant()->uid, $activityUid );
    }

    /**
     * @param SaveApplicationsAndEquipmentRequest $request
     * @param $activityUid
     * @return mixed
     */
    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $activityUid ) {

        return $this->activityService->saveApplicationsAndEquipments( $request, $this->getTenant()->uid, $activityUid );
    }


    /**
     * @param string $range
     * @return mixed
     */
    public function export( $range = 'all' ) {
        $response = $this->activityService->export( $this->getTenant(), $range );

        return $response->deleteFileAfterSend( false );
    }
}
