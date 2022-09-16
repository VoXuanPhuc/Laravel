<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\Application;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Services\Interfaces\ApplicationServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ApplicationService implements ApplicationServiceInterface
{

    public function __construct(
        protected ApplicationRepositoryInterface $applicationRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @param $organizationUid
     * @return LengthAwarePaginator
     */
    public function listApplications($organizationUid)
    {
        /** @var Organization $organization */
        $organization = $this->organizationService->getOrganization( $organizationUid );

        return $organization->applications()->paginate(config('config.pagination_size'));
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return Application
     * @throws NotFoundException
     */
    public function getApplication($organizationUid, $uid)
    {
        $application = $this->applicationRepository->findByUid( $uid );

        if( !$application ) {
            throw new NotFoundException(__('activity::app.application.not_found'));
        }

        return $application;
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @return mixed
     */
    public function createApplication(Request $request, $organizationUid)
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );

        $request->merge([
            'organization_id' => $organization->id
        ]);

        return $this->applicationRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateApplication(Request $request, $organizationUid, $uid)
    {
        $application = $this->getApplication( $organizationUid, $uid );

        return $this->applicationRepository->update( $request->all(), $application->id );

    }

    /**
     * @throws NotFoundException
     */
    public function deleteApplication($organizationUid, $uid)
    {
        $application = $this->getApplication( $organizationUid, $uid );

        return $this->applicationRepository->delete( $application->id );
    }
}
