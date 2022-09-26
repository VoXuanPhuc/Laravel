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
     * @return LengthAwarePaginator
     */
    public function listRemoteAccessFactors()
    {
        return $this->remoteAccessFactorRepository->paginate(config('config.pagination_size'));
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getRemoteAccessFactor( $uid )
    {
        $remoteAccessFactor = $this->remoteAccessFactorRepository->findbyUid($uid);

        if (!$remoteAccessFactor) {
            throw new NotFoundException(__('activity::app.remote_access_factor.not_found'));
        }

        return $remoteAccessFactor;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createRemoteAccessFactor( Request $request )
    {
        return $this->remoteAccessFactorRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateRemoteAccessFactor( Request $request, $uid )
    {
        $remoteAccessFactor = $this->getRemoteAccessFactor( $uid );

        return $this->remoteAccessFactorRepository->update( $request->all(), $remoteAccessFactor->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteRemoteAccessFactor( $uid )
    {
        $remoteAccessFactor = $this->getRemoteAccessFactor( $uid );

        return $this->remoteAccessFactorRepository->delete( $remoteAccessFactor->id );
    }
}
