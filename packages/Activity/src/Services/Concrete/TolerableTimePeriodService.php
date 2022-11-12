<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\TolerableTimePeriod;
use Encoda\Activity\Repositories\Interfaces\TolerableTimePeriodRepositoryInterface;
use Encoda\Activity\Services\Interfaces\TolerableTimePeriodServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TolerableTimePeriodService implements TolerableTimePeriodServiceInterface
{

    public function __construct(
        protected TolerableTimePeriodRepositoryInterface $tolerableTimePeriodRepository,
        protected OrganizationServiceInterface           $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listTolerableTimePeriods()
    {
        $query = $this->tolerableTimePeriodRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->tolerableTimePeriodRepository->applyPaging($query);
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
            ->setTable(TolerableTimePeriod::getTableName())
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
            ->setTable(TolerableTimePeriod::getTableName())
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
    public function getTolerableTimePeriod($uid )
    {
        $tolerableTimePeriod = $this->tolerableTimePeriodRepository->findbyUid($uid);

        if (!$tolerableTimePeriod) {
            throw new NotFoundException(__('activity::app.remote_access_factor.not_found'));
        }

        return $tolerableTimePeriod;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createTolerableTimePeriod(Request $request )
    {
        return $this->tolerableTimePeriodRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateTolerableTimePeriod(Request $request, $uid )
    {
        $TolerablePeriodDisruption = $this->getTolerableTimePeriod( $uid );

        return $this->tolerableTimePeriodRepository->update( $request->all(), $TolerablePeriodDisruption->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteTolerablePeriod($uid )
    {
        $tolerablePeriodDisruption = $this->getTolerableTimePeriod( $uid );

        return $this->tolerableTimePeriodRepository->delete( $tolerablePeriodDisruption->id );
    }

    /**
     * @return mixed
     */
    public function listAllTolerableTimePeriods()
    {
        return $this->tolerableTimePeriodRepository->all();
    }
}
