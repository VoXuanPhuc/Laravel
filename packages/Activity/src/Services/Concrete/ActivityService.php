<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Exports\ActivityExport;
use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\SaveRemoteAccessRequest;
use Encoda\Activity\Http\Requests\Activity\SaveApplicationsAndEquipmentRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Models\Activity;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\EquipmentRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ActivityService implements ActivityServiceInterface
{

    public function __construct(
        protected ActivityRepositoryInterface           $activityRepository,
        protected DivisionServiceInterface              $divisionService,
        protected BusinessUnitServiceInterface          $businessUnitService,
        protected RoleRepositoryInterface               $roleRepository,
        protected AlternativeRoleRepositoryInterface    $alternativeRoleRepository,
        protected UtilityRepositoryInterface            $utilityRepository,
        protected RemoteAccessFactorRepositoryInterface $remoteAccessFactorRepository,
        protected EquipmentRepositoryInterface          $equipmentRepository,
        protected ApplicationRepositoryInterface        $applicationRepository,
        protected OrganizationServiceInterface          $organizationService
    )
    {
    }

    /**
     * @param $organizationUid
     * @return mixed
     */
    public function listActivities($organizationUid, ): mixed
    {
        /** @var Organization $organization */
       $organization = $this->organizationService->getOrganization( $organizationUid );

       return $this->activityRepository->paginate(config('config.pagination_size'));
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
   public function getActivity($organizationUid, $uid): mixed
   {
       $activity = $this->activityRepository->findByUid( $uid );

       if( !$activity ) {
           throw new NotFoundException( __('activity::app.activity.not_found'));
       }

       return $activity->load(
           [
               'division',
               'businessUnit',
               'roles',
               'alternativeRoles',
               'utilities',
               'remoteAccessFactors',
               'applications',
               'itSolution',
               'equipments'
           ]);
   }

    /**
     * @param CreateActivityRequest $request
     * @param $organizationUid
     * @return mixed
     * @throws ServerErrorException
     */
   public function createActivity(CreateActivityRequest $request, $organizationUid ): mixed
   {

       // Division or Business Unit
       if( empty( $request->division['uid'] ) && empty( $request->business_unit['uid'] ) ) {
           throw new BadRequestException('Activity must belong to a divsion or business unit');
       }

       if( !empty( $request->division['uid'] ) ) {
           $division = $this->divisionService->getDivision( $organizationUid, $request->division['uid']);
           $request->merge( ['division_id' => $division->id] );
       }

       if( !empty( $request->business_unit['uid'] ) ) {
           $business = $this->businessUnitService->getBusinessUnitWithoutDivision( $organizationUid, $request->business_unit['uid']);
           $request->merge( ['business_unit_id' => $business->id] );
       }

       try{

           DB::beginTransaction();

           /** @var Activity $activity */
           $activity = $this->activityRepository->create( $request->only( $this->activityRepository->getFillable()));

           // Roles
           $roleIds = $this->getRoleIds($request->roles );
           $activity->roles()->sync( $roleIds );

           // Alternative roles
           $alternativeRoleIds = $this->getAlternativeRoleIds( $request->alternative_roles );
           $activity->alternativeRoles()->sync( $alternativeRoleIds );

           // Utilities
           $utilityIds = $this->getUtilityIds( $request->utilities );
           $activity->utilities()->sync( $utilityIds );

           DB::commit();
       }
       catch ( Throwable $e ) {
           Log::error($e);
           DB::rollBack();
           throw new ServerErrorException(__('activity::app.activity.update_error'));
       }


       return $activity->load(['division','businessUnit','roles','alternativeRoles', 'utilities']);

   }

    /**
     * @param UpdateActivityRequest $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     * @throws ServerErrorException
     */
   public function updateActivity(UpdateActivityRequest $request,$organizationUid, $uid): mixed
    {

        // Division or Business Unit
        if( empty( $request->division['uid'] ) && empty( $request->business_unit['uid'] ) ) {
            throw new BadRequestException('Activity must belong to a divsion or business unit');
        }

        if( !empty( $request->division['uid'] ) ) {
            $division = $this->divisionService->getDivision( $organizationUid, $request->division['uid']);
            $request->merge( ['division_id' => $division->id] );
        }

        if( !empty( $request->business_unit['uid'] ) ) {
            $business = $this->businessUnitService->getBusinessUnitWithoutDivision( $organizationUid, $request->business_unit['uid']);
            $request->merge( ['business_unit_id' => $business->id] );
        }


        $activity = $this->getActivity( $organizationUid, $uid );

        try{

            DB::beginTransaction();

            /** @var Activity $activity */
            $activity = $this->activityRepository->update( $request->only( $this->activityRepository->getFillable()), $activity->id );

            // Roles
            $roleIds = $this->getRoleIds($request->roles );
            $activity->roles()->sync( $roleIds );

            // Alternative roles
            $alternativeRoleIds = $this->getRoleIds( $request->alternative_roles );
            $activity->alternativeRoles()->sync( $alternativeRoleIds );

            // Utilities
            $utilityIds = $this->getUtilityIds( $request->utilities );
            $activity->utilities()->sync( $utilityIds );

            DB::commit();
        }
        catch ( Throwable $e ) {
            Log::error($e);
            DB::rollBack();
            throw new ServerErrorException(__('activity::app.activity.update_error'));
        }


        return $activity->load(['division','businessUnit','roles','alternativeRoles', 'utilities']);
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return int
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function deleteActivity($organizationUid,  $uid): int
    {
        $activity = $this->getActivity($organizationUid, $uid);

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
     * @param array $roles
     * @return array
     */
    private function getRoleIds(array $roles): array
    {

         $roleObjs = $this->roleRepository->findWhereIn('uid',
             array_map(function ($role){
                 return $role['uid'];
             }, $roles),
             'id');
         return $this->fillDataToArray($roleObjs);
    }

    /**
     * @param array $alternativeRoles
     * @return array
     */
    private function getAlternativeRoleIds(array $alternativeRoles): array
    {
        $alternativeRoleObjs = $this->alternativeRoleRepository->findWhereIn('uid',
            array_map(function ($role){
                return $role['uid'];
            }, $alternativeRoles),
             'id');
        return $this->fillDataToArray($alternativeRoleObjs);
    }

    /**
     * @param array $utilities
     * @return array
     */
    private function getUtilityIds(array $utilities): array
    {
        $utilityObjs = $this->utilityRepository->findWhereIn(
        'uid',
            array_map(function ($u){
                return $u['uid'];
            }, $utilities),
    'id');
        return $this->fillDataToArray($utilityObjs);
    }

    /**
     * @param array $remoteAccessFactors
     * @return array
     */
    private function getRemoteAccessFactorIds(array $remoteAccessFactors): array
    {
        $remoteAccessFactorObjs = $this->remoteAccessFactorRepository->findWhereIn('uid', array_map(function($raf){
            return $raf['uid'];
        }, $remoteAccessFactors),
'id');

        return $this->fillDataToArray($remoteAccessFactorObjs);
    }

    /**
     * @param array $applications
     * @return array
     */
    private function getApplicationIds(array $applications): array
    {
        $applicationObjs = $this->applicationRepository->findWhereIn('uid', array_map( function($app) {
            return $app['uid'];
        }, $applications ), 'id');
        return $this->fillDataToArray($applicationObjs);
    }

    /**
     * @param array $devices
     * @return array
     */
    private function getEquipmentIds(array $devices): array
    {
        $deviceObjs = $this->equipmentRepository->findWhereIn('uid', array_map( function($equipment) {
            return $equipment['uid'];
        }, $devices ), 'id');
        return $this->fillDataToArray($deviceObjs);
    }

    /**
     * @param $items
     * @return array
     */
    private function fillDataToArray($items): array
    {
        if (empty($items)) {
            return [];
        }

        $data = [];
        foreach ($items as $item)
        {
            $data[] = $item->id;
        }

        return $data;
    }

    /**
     * @param $organizationUid
     * @return mixed
     */
    public function getOrgActivities($organizationUid): mixed
    {
        $organization = $this->organizationService->getOrganization($organizationUid);
        return $organization->activities()->paginate(config('config.pagination_size'));
    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @return mixed
     */
    public function getDivisionActivities($organizationUid, $divisionUid): mixed
    {
        $division = $this->divisionService->getDivision($organizationUid, $divisionUid);
        return $division->activities()->paginate(config('config.pagination_size'));
    }

    /**
     * @throws NotFoundException
     */
    public function saveRemoteAccessFactors(SaveRemoteAccessRequest $request, $organizationUid, $activityUid)
    {
        /** @var Activity $activity */
        $activity = $this->getActivity( $organizationUid, $activityUid );

        try {

            // Update activity
            $this->activityRepository->update( $request->all(), $activity->id );

            // Remote access factor
            $remoteAccessFactorIds = $this->getRemoteAccessFactorIds( $request->remote_access_factors );
            $activity->remoteAccessFactors()->sync( $remoteAccessFactorIds );

                   }
        catch ( Throwable $e ) {

            Log::error( $e );
            throw new ServerErrorException(  __('activity::app.activity.update_remote_access_factor_error') );
        }

        return $activity->refresh();
    }

    /**
     * @param SaveApplicationsAndEquipmentRequest $request
     * @param $organizationUid
     * @param $activityUid
     * @return Activity
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function saveApplicationsAndEquipments(SaveApplicationsAndEquipmentRequest $request, $organizationUid, $activityUid)
    {
        /** @var Activity $activity */
        $activity = $this->getActivity( $organizationUid, $activityUid );

        try {

            // Update activity
            $this->activityRepository->update( $request->all(), $activity->id );


            // Applications
            $applicationIds = $this->getApplicationIds( $request->applications );
            $activity->applications()->sync( $applicationIds );

            // Equipments
            $equipmentIds = $this->getEquipmentIds( $request->equipments );
            $activity->equipments()->sync( $equipmentIds );

            // IT solution
            $activity->itSolution()->updateOrCreate( ['uid' => $request->it_solution['uid'] ?? '' ], $request->it_solution );
        }
        catch ( Throwable $e ) {

            Log::error( $e );
            throw new ServerErrorException(  __('activity::app.activity.update_remote_access_factor_error') );
        }

        return $activity->refresh();
    }

    /**
     * @throws NotFoundException|ServerErrorException
     */
    public function permanentDelete($organizationUid, $uid )
    {
        /** @var Activity $activity */
        $activity = $this->getActivity( $organizationUid, $uid );

        try {
            $activity->forceDelete();
        }
        catch (Throwable $e) {
            throw new ServerErrorException('Unable to cancel activity');
        }

        return true;
    }

    /**
     * @param $organization
     * @param string $divisionUid
     * @param string $businessUnitUid
     * @param string $range
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($organization, $divisionUid = '', $businessUnitUid = '', $range = 'all')
    {
        $fileName = 'Activities_'. time(). '.xlsx';

        $data = $this->activityRepository->with(['division','businessUnit','roles','alternativeRoles', 'utilities'])->all();

        $export = new ActivityExport( $data );

        return Excel::download( $export, $fileName );
    }


}