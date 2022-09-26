<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityRemoteAccessServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityRemoteAccessService extends BaseActivityService implements ActivityRemoteAccessServiceInterface
{

    public function __construct(
        protected ActivityServiceInterface $activityService,
        protected ActivityRepositoryInterface $activityRepository,
        protected RemoteAccessFactorRepositoryInterface $accessFactorRepository
    )
    {
    }

    /**
     * @throws ServerErrorException|NotFoundException
     */
    public function saveRemoteAccessFactors(SaveRemoteAccessRequest $request, $activityUid )
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findByUid( $activityUid );

        if( !$activity ) {
            throw new NotFoundException( __('activity::app.activity.not_found'));
        }

        try {

            // Update activity
            $this->activityRepository->update( $request->all(), $activity->id );

            // Remote access factor
            $activity->remoteAccessFactors()->sync(
                $this->getRemoteAccessFactors( $request->remote_access_factors )
            );

        }
        catch ( Throwable $e ) {

            Log::error( $e );
            throw new ServerErrorException(  __('activity::app.activity.update_remote_access_factor_error') );
        }

        return $activity;
    }


    /**
     * @param $rafs
     * @return mixed
     */
    protected function getRemoteAccessFactors( $rafs ) {

        return $this->accessFactorRepository->findByUids(
            array_map( function( $raf ) { return $raf['uid']; }, $rafs ),
            'id'
        );
    }
}
