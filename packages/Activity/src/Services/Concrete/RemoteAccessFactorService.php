<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Services\Interfaces\RemoteAccessFactorServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class RemoteAccessFactorService implements RemoteAccessFactorServiceInterface
{

    public function __construct(
        protected RemoteAccessFactorRepositoryInterface $remoteAccessFactorRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @param $organizationUid
     * @return LengthAwarePaginator
     */
    public function listRemoteAccessFactors($organizationUid)
    {
        /** @var Organization $organization */
        $organization = $this->organizationService->getOrganization( $organizationUid );

        return $organization->remoteAccessFactors()->paginate(config('config.pagination_size'));
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getRemoteAccessFactor($organizationUid, $uid)
    {
        $remoteAccessFactor = $this->remoteAccessFactorRepository->findbyUid($uid);

        if (!$remoteAccessFactor) {
            throw new NotFoundException(__('activity::app.remote_access_factor.not_found'));
        }

        return $remoteAccessFactor;
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @return mixed
     */
    public function createRemoteAccessFactor(Request $request, $organizationUid)
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );

        $request->merge([
            'organization_id' => $organization->id
        ]);

        return $this->remoteAccessFactorRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateRemoteAccessFactor(Request $request, $organizationUid, $uid)
    {
        $RemoteAccessFactor = $this->getRemoteAccessFactor( $organizationUid, $uid );

        return $this->remoteAccessFactorRepository->update( $request->all(), $RemoteAccessFactor->id );
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteRemoteAccessFactor($organizationUid, $uid)
    {
        $RemoteAccessFactor = $this->getRemoteAccessFactor( $organizationUid, $uid );

        return $this->remoteAccessFactorRepository->delete( $RemoteAccessFactor->id );
    }
}
