<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Exports\ActivityExport;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ActivityService extends BaseActivityService implements ActivityServiceInterface
{

    use ActivityServiceTrait;

    public function __construct(
        protected ActivityRepositoryInterface           $activityRepository,
        protected DivisionServiceInterface              $divisionService,
        protected BusinessUnitServiceInterface          $businessUnitService,
        protected RoleRepositoryInterface               $roleRepository,
        protected AlternativeRoleRepositoryInterface    $alternativeRoleRepository,
        protected UtilityRepositoryInterface            $utilityRepository,
        protected UserRepositoryInterface               $userRepository,
    )
    {
    }

    /**
     * @return mixed
     * @throws ValidationException
     */
    public function listActivities(): mixed
    {
        $query = $this->activityRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->activityRepository->applyPaging($query);
    }

    /**
     * @param $query
     *
     * @throws ValidationException
     */
    public function applySearchFilter($query)
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(Activity::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(
                ['name', 'description', 'on_site_requires', 'is_remote', 'min_people', 'status', 'step']
            )
            ->validate()
            ->applyFilter();
        return $query;
    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySortFilter($query): void
    {
        //Apply sort
        SortFluent::init()
            ->setTable(Activity::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'is_remote', 'min_people', 'status', 'step'])
            ->validate()
            ->applySort();
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
   public function getActivity( $uid ): mixed
   {
       $activity = $this->activityRepository->findByUid( $uid );

       if( !$activity ) {
           throw new NotFoundException( __('activity::app.activity.not_found'));
       }

       return $activity->load( $this->relationsQuery() );
   }


    /**
     * @param $request
     */
   protected function setDivisionAndBusinessUnitIds( $request ) {
       if( !empty( $request->division['uid'] ) ) {
           $division = $this->divisionService->getDivision( $request->division['uid']);
           $request->merge( ['division_id' => $division->id] );
       }

       if( !empty( $request->business_unit['uid'] ) ) {
           $business = $this->businessUnitService->getBusinessUnitWithoutDivision( $request->business_unit['uid']);
           $request->merge( ['business_unit_id' => $business->id] );
       }

   }


    /**
     * @param CreateActivityRequest $request
     * @return mixed
     * @throws ServerErrorException
     */
   public function createActivity( CreateActivityRequest $request ): mixed
   {

       $this->setDivisionAndBusinessUnitIds( $request );

       try{

           DB::beginTransaction();

           /** @var Activity $activity */
           $activity = $this->activityRepository->create( $request->only( $this->activityRepository->getFillable()));

           // Assignee
           $activity->assignee()->associate(
               $this->getUser( $request->assignee )
           )->save();

           // Roles
           $activity->roles()->sync(
               $this->getRoles($request->roles )
           );

           // Alternative roles
           $activity->alternativeRoles()->sync(
               $this->getAlternativeRoles( $request->alternative_roles )
           );

           // Utilities
           $activity->utilities()->sync(
               $this->getUtilities( $request->utilities )
           );

           DB::commit();
       }
       catch ( Throwable $e ) {
           Log::error($e);
           DB::rollBack();
           throw new ServerErrorException(__('activity::app.activity.update_error'));
       }


       return $activity;

   }

    /**
     * @param UpdateActivityRequest $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     * @throws ServerErrorException
     */
   public function updateActivity(UpdateActivityRequest $request, $uid): mixed
    {

        $activity = $this->getActivity( $uid );

        $this->setDivisionAndBusinessUnitIds( $request );

        try{

            DB::beginTransaction();

            /** @var Activity $activity */
            $activity = $this->activityRepository->update(
                $request->only( $this->activityRepository->getFillable()
                ), $activity->id );

            // Roles
            $activity->assignee()->associate(
                $this->getUser($request->assignee )
            )->save();

            // Roles
            $activity->roles()->sync(
                $this->getRoles($request->roles )
            );

            // Alternative roles
            $activity->alternativeRoles()->sync(
                $this->getAlternativeRoles( $request->alternative_roles )
            );

            // Utilities
            $activity->utilities()->sync(
                $this->getUtilities( $request->utilities )
            );

            DB::commit();
        }
        catch ( Throwable $e ) {
            Log::error($e);
            DB::rollBack();
            throw new ServerErrorException(__('activity::app.activity.update_error'));
        }


        return $activity;
    }

    /**
     * @param $uid
     * @return int
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function deleteActivity( $uid ): int
    {
        $activity = $this->getActivity( $uid );

        DB::beginTransaction();
        try {
            $activity->itSolution()->delete();
            $this->activityRepository->delete($activity->id);
        }
        catch (Exception $e) {
            DB::rollback();
            throw new BadRequestException( __('activity::app.activity.delete_error'));
        }

        DB::commit();

        return true;
    }


    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function getOrgActivities(): mixed
    {
        /** @var Organization $organization */
        $organization = tenant();
        return $organization->activities()->paginate(config('config.pagination_size'));
    }

    /**
     * @param $divisionUid
     * @return mixed
     */
    public function getDivisionActivities( $divisionUid ): mixed
    {
        $division = $this->divisionService->getDivision( $divisionUid );
        return $division->activities()->paginate(config('config.pagination_size'));
    }


    /**
     * @throws NotFoundException|ServerErrorException
     */
    public function permanentDelete( $uid )
    {
        /** @var Activity $activity */
        $activity = $this->getActivity( $uid );

        try {
            $activity->forceDelete();
        }
        catch (Throwable $e) {
            throw new ServerErrorException('Unable to cancel activity');
        }

        return true;
    }

    /**
     * @param string $divisionUid
     * @param string $businessUnitUid
     * @param string $range
     * @return BinaryFileResponse
     */
    public function export( $divisionUid = '', $businessUnitUid = '', $range = 'all')
    {
        $fileName = 'Activities_'. time(). '.xlsx';

        $data = $this->activityRepository
            ->with(['division','businessUnit','roles','alternativeRoles', 'utilities'])->all();

        $export = new ActivityExport( $data );

        return Excel::download( $export, $fileName );
    }


    /**
     * @return mixed
     */
    public function top()
    {
        return $this->activityRepository
            ->orderBy('created_at', 'DESC')
            ->take(2)
            ->all()
            ;
    }
}
