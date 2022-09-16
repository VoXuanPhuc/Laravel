<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UtilityService implements UtilityServiceInterface
{

    public function __construct(
        protected UtilityRepositoryInterface $utilityRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @param $organizationUid
     * @return LengthAwarePaginator
     */
    public function listUtilities($organizationUid)
    {
        /** @var Organization $organization */
        $organization = $this->organizationService->getOrganization( $organizationUid );

        return $organization->utilities()->paginate(config('config.pagination_size'));
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getUtility($organizationUid, $uid)
    {
        $Utility = $this->utilityRepository->findbyUid($uid);

        if (!$Utility) {
            throw new NotFoundException(__('activity::app.Utility.not_found'));
        }

        return $Utility;
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @return mixed
     */
    public function createUtility(Request $request, $organizationUid)
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );

        $request->merge([
            'organization_id' => $organization->id
        ]);

        return $this->utilityRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateUtility(Request $request, $organizationUid, $uid)
    {
        $Utility = $this->getUtility( $organizationUid, $uid );

        return $this->utilityRepository->update( $request->all(), $Utility->id );
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteUtility($organizationUid, $uid)
    {
        $Utility = $this->getUtility( $organizationUid, $uid );

        return $this->utilityRepository->delete( $Utility->id );
    }
}
