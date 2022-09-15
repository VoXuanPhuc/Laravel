<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Http\Requests\Activity\CreateActivityRequest;
use Encoda\Activity\Http\Requests\Activity\UpdateActivityRequest;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\DeviceRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class ActivityService implements ActivityServiceInterface
{
    
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository,
        protected DivisionServiceInterface $divisionService,
        protected BusinessUnitServiceInterface $businessUnitService,
        protected RoleRepositoryInterface $roleRepository,
        protected AlternativeRoleRepositoryInterface $alternativeRoleRepository,
        protected UtilityRepositoryInterface $utilityRepository,
        protected RemoteAccessFactorRepositoryInterface $remoteAccessFactorRepository,
        protected DeviceRepositoryInterface $deviceRepository,
        protected ApplicationRepositoryInterface $applicationRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @return mixed
     */
    public function listActivities($organizationUid, $divisionUid, $businessUnitUid): mixed
    {
        $businessUnit = $this->businessUnitService->getBusinessUnit($organizationUid, $divisionUid, $businessUnitUid);
        
        if (empty($businessUnit)) {
            return [];
        }
    
        return $businessUnit->activities()->with('businessUnit')->paginate(config('config.pagination_size'));
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
   public function getActivity($organizationUid, $divisionUid, $businessUnitUid, $uid): mixed
   {
       $businessUnit = $this->businessUnitService->getBusinessUnit($organizationUid, $divisionUid, $businessUnitUid);
       
       $activity = $this->activityRepository->findOneWhere(
           [
               ['division_id', '=', $businessUnit->division_id],
               ['business_unit_id', '=', $businessUnit->id],
               ['uid', '=', $uid]
           ]
       );
    
       if( !$activity ) {
           throw new NotFoundException( __('activity::app.activity.not_found'));
       }
    
       return $activity->load(['businessUnit', 'roles', 'utilities', 'remoteAccessesFactors', 'applications', 'iTSolution', 'devices']);
   }
   
    /**
     * @param CreateActivityRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @return mixed
     * @throws BadRequestException
     */
   public function createActivity(CreateActivityRequest $request, $organizationUid, $divisionUid, $businessUnitUid): mixed
   {
       $businessUnit = $this->businessUnitService->getBusinessUnit($organizationUid, $divisionUid, $businessUnitUid);
       $request->merge(
           [
               'division_id' => $businessUnit->division_id,
               'business_unit_id' => $businessUnit->id,
           ]
       );
       
       $roleIds = $this->getRoleIds($request->get('role_uids'));
       $alternativeRoleIds = $this->getAlternativeRoleIds($request->get('alternative_role_uids'));
       $utilityIds = $this->getUtilityIds($request->get('utility_uids'));
       $remoteAccessFactorIds = $this->getRemoteAccessFactorIds($request->get('remote_access_factor_uids'));
       $applicationIds = $this->getApplicationIds($request->get('application_uids'));
       $deviceIds = $this->getDeviceIds($request->get('device_uids'));
       
       $iTSolution = $request->only(['data_type', 'location']);
        
        DB::beginTransaction();
        try {
            $activity = $this->activityRepository->create($request->only($this->activityRepository->getFillable()));
            $this->syncPivotTables($activity, $roleIds, $alternativeRoleIds, $utilityIds, $remoteAccessFactorIds, $applicationIds, $deviceIds);
            $activity->iTSolution()->create($iTSolution);
        } catch (Exception $e) {
            DB::rollback();
            throw new BadRequestException( __('activity::app.activity.create_error') );
        }
    
        DB::commit();
       
        return $activity->load('businessUnit');
   }
    
    /**
     * @param UpdateActivityRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return mixed
     * @throws BadRequestException
     * @throws NotFoundException
     */
   public function updateActivity(UpdateActivityRequest $request,$organizationUid, $divisionUid, $businessUnitUid, $uid): mixed
    {
        $businessUnit = $this->businessUnitService->getBusinessUnit($organizationUid, $divisionUid, $businessUnitUid);
        $request->merge(
            [
                'division_id' => $businessUnit->division_id,
                'business_unit_id' => $businessUnit->id,
            ]
        );
    
        $roleIds = $this->getRoleIds($request->get('role_uids'));
        $alternativeRoleIds = $this->getAlternativeRoleIds($request->get('alternative_role_uids'));
        $utilityIds = $this->getUtilityIds($request->get('utility_uids'));
        $remoteAccessFactorIds = $this->getRemoteAccessFactorIds($request->get('remote_access_factor_uids'));
        $applicationIds = $this->getApplicationIds($request->get('application_uids'));
        $deviceIds = $this->getDeviceIds($request->get('device_uids'));
        
        $activity = $this->getActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);
        
        $iTSolution = $request->only(['data_type', 'location']);
       
        DB::beginTransaction();
    
        try {
            $activity = $this->activityRepository->update($request->only($this->activityRepository->getFillable()), $activity->id);
            $this->syncPivotTables($activity, $roleIds, $alternativeRoleIds, $utilityIds, $remoteAccessFactorIds, $applicationIds, $deviceIds);
            $activity->iTSolution()->update($iTSolution);
        } catch (Exception $e) {
            DB::rollback();
            throw new BadRequestException( __('activity::app.activity.update_error') );
        }
    
        DB::commit();
        
        return $activity->load(['roles', 'utilities', 'remoteAccessesFactors', 'applications', 'iTSolution', 'devices']);
    }
    
    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $businessUnitUid
     * @param $uid
     * @return int
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function deleteActivity($organizationUid, $divisionUid, $businessUnitUid, $uid): int
    {
        $activity = $this->getActivity($organizationUid, $divisionUid, $businessUnitUid, $uid);
    
        DB::beginTransaction();
        try {
            $activity->iTSolution()->delete();
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
     * @param $activity
     * @param array $roleIds
     * @param array $alternativeRoleIds
     * @param array $utilityIds
     * @param array $remoteAccessFactorIds
     * @param array $applicationIds
     * @param array $deviceIds
     * @return void
     */
    private function syncPivotTables($activity, array $roleIds, array $alternativeRoleIds, array $utilityIds, array $remoteAccessFactorIds, array $applicationIds, array $deviceIds): void
    {
        $activity->roles()->sync($roleIds);
        $activity->alternativeRoles()->sync($alternativeRoleIds);
        $activity->utilities()->sync($utilityIds);
        $activity->remoteAccessesFactors()->sync($remoteAccessFactorIds);
        $activity->applications()->sync($applicationIds);
        $activity->devices()->sync($deviceIds);
    }
    
    /**
     * @param array $roleUIds
     * @return array
     */
    private function getRoleIds(array $roleUIds): array
    {
         $roles = $this->roleRepository->findWhereIn('uid', $roleUIds, 'id');
         return $this->fillDataToArray($roles);
    }
    
    /**
     * @param array $alternativeRoleIds
     * @return array
     */
    private function getAlternativeRoleIds(array $alternativeRoleIds): array
    {
        $alternativeRoles = $this->alternativeRoleRepository->findWhereIn('uid', $alternativeRoleIds, 'id');
        return $this->fillDataToArray($alternativeRoles);
    }
    
    /**
     * @param array $utilityUIds
     * @return array
     */
    private function getUtilityIds(array $utilityUIds): array
    {
        $utilities = $this->utilityRepository->findWhereIn('uid', $utilityUIds, 'id');
        return $this->fillDataToArray($utilities);
    }
    
    /**
     * @param array $remoteAccessFactorUIds
     * @return array
     */
    private function getRemoteAccessFactorIds(array $remoteAccessFactorUIds): array
    {
        $remoteAccessFactors = $this->remoteAccessFactorRepository->findWhereIn('uid', $remoteAccessFactorUIds, 'id');
        return $this->fillDataToArray($remoteAccessFactors);
    }
    
    /**
     * @param array $applicationUIds
     * @return array
     */
    private function getApplicationIds(array $applicationUIds): array
    {
        $applications = $this->applicationRepository->findWhereIn('uid', $applicationUIds, 'id');
        return $this->fillDataToArray($applications);
    }
    
    /**
     * @param array $deviceUIds
     * @return array
     */
    private function getDeviceIds(array $deviceUIds): array
    {
        $devices = $this->deviceRepository->findWhereIn('uid', $deviceUIds, 'id');
        return $this->fillDataToArray($devices);
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
}
