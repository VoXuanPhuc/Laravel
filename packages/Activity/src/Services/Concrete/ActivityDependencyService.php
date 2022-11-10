<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\SaveDependenciesAndSuppliersRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityDependencyServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Dependency\Repositories\Interfaces\DependencyScenarioRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityDependencyService extends BaseActivityService implements ActivityDependencyServiceInterface
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected SupplierRepositoryInterface $supplierRepository,
        protected DependencyScenarioRepositoryInterface $dependencyScenarioRepository,
    )
    {
    }

    /**
     * @param SaveDependenciesAndSuppliersRequest $request
     * @param $activityUid
     * @return Activity
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function saveDependenciesAndSuppliers(SaveDependenciesAndSuppliersRequest $request, $activityUid): Activity
    {
        /** @var Activity $activity */
        $activity = $this->activityRepository->findByUid( $activityUid );

        if( !$activity ) {
            throw new NotFoundException( __('activity::app.activity.not_found'));
        }

        try {
            // Update activity
            $this->activityRepository->update( $request->all(), $activity->id );

            $activity->dependencyScenarios()->sync(
                $this->getDependencyScenarios( $request->dependency_scenarios )
            );

            $activity->suppliers()->sync(
                $this->getSuppliers( $request->suppliers )
            );
        }
        catch ( Throwable $e ) {

            Log::error( $e );
            throw new ServerErrorException(  __('activity::app.activity.update_remote_access_factor_error') );
        }

        return $activity;
    }

    /**
     * @param $dependencyScenarios
     * @return mixed
     */
    protected function getDependencyScenarios( $dependencyScenarios )
    {
        return $this->dependencyScenarioRepository->findByUids(
            array_map( function( $dependencyScenario ) { return $dependencyScenario['uid']; },
                $dependencyScenarios ),
            'id'
        );
    }

    /**
     * @param $suppliers
     * @return mixed
     */
    protected function getSuppliers( $suppliers )
    {
        return $this->supplierRepository->findByUids(
            array_map( function( $supplier ) { return $supplier['uid']; }, $suppliers ),
            'id'
        );
    }
}
