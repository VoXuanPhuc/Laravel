<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveSoftwareAndEquipmentRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
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

    /**
     * @param SaveRemoteAccessRequest $request
     * @param $activityUid
     * @return mixed
     */
    public function saveRemoteAccessFactors( SaveRemoteAccessRequest $request, $activityUid ) {

        return $this->activityService->saveRemoteAccessFactors( $request, $this->getTenant()->uid, $activityUid );
    }

    /**
     * @param SaveSoftwareAndEquipmentRequest $request
     * @param $activityUid
     * @return mixed
     */
    public function saveSoftwareAndEquipments( SaveSoftwareAndEquipmentRequest $request, $activityUid ) {

        return $this->activityService->saveSoftwareAndEquipments( $request, $this->getTenant()->uid, $activityUid );
    }
}
