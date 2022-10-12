<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\RemoteAccessFactor;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Services\Interfaces\RemoteAccessFactorServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @throws ValidationException
     */
    public function listRemoteAccessFactors()
    {
        $query = $this->remoteAccessFactorRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->remoteAccessFactorRepository->applyPaging($query);
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
            ->setTable(RemoteAccessFactor::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description'])
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
            ->setTable(RemoteAccessFactor::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name'])
            ->validate()
            ->applySort();
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
