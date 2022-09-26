<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\EquipmentRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityEquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityEquipmentService extends BaseActivityService implements ActivityEquipmentServiceInterface
{

    public function __construct(
        protected ActivityServiceInterface $activityService,
        protected ActivityRepositoryInterface $activityRepository,
        protected ApplicationRepositoryInterface $applicationRepository,
        protected EquipmentRepositoryInterface $equipmentRepository,
    )
    {
    }

    /**
     * @param SaveApplicationsAndEquipmentRequest $request
     * @param $activityUid
     * @return Activity
     * @throws ServerErrorException|NotFoundException
     */
    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $activityUid): Activity
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findByUid( $activityUid );

        if( !$activity ) {
            throw new NotFoundException( __('activity::app.activity.not_found'));
        }

        try {

            // Update activity
            $this->activityRepository->update( $request->all(), $activity->id );


            // Applications
            $activity->applications()->sync(
                $this->getApplications( $request->applications )
            );

            // Equipments
            $activity->equipments()->sync(
                $this->getEquipments( $request->equipments )
            );

            // IT solution
            $activity->itSolution()->updateOrCreate(
                ['uid' => $request->it_solution['uid'] ?? '' ],
                $request->it_solution
            );
        }
        catch ( Throwable $e ) {

            Log::error( $e );
            throw new ServerErrorException(  __('activity::app.activity.update_remote_access_factor_error') );
        }

        return $activity;
    }


    /**
     * @param $apps
     * @return mixed
     */
    protected function getApplications( $apps ): mixed
    {

        return $this->applicationRepository->findByUids(
            array_map( function( $app ) { return $app['uid']; }, $apps ),
            'id'
        );
    }


    /**
     * @param $equipments
     * @return mixed
     */
    protected function getEquipments( $equipments ): mixed
    {

        return $this->equipmentRepository->findByUids(
            array_map( function( $eq ) { return $eq['uid']; }, $equipments ),
            'id'
        );
    }
}
