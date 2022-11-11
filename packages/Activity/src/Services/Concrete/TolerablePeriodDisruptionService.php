<?php

namespace Encoda\Activity\Services\Concrete;

use Encoda\Activity\Models\TolerablePeriodDisruption;
use Encoda\Activity\Repositories\Interfaces\TolerablePeriodDisruptionRepositoryInterface;
use Encoda\Activity\Services\Interfaces\TolerablePeriodDisruptionServiceInterface;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TolerablePeriodDisruptionService implements TolerablePeriodDisruptionServiceInterface
{

    public function __construct(
        protected TolerablePeriodDisruptionRepositoryInterface $TolerablePeriodDisruptionRepository,
        protected OrganizationServiceInterface $organizationService
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    public function listTolerablePeriodDisruptions()
    {
        $query = $this->TolerablePeriodDisruptionRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->TolerablePeriodDisruptionRepository->applyPaging($query);
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
            ->setTable(TolerablePeriodDisruption::getTableName())
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
            ->setTable(TolerablePeriodDisruption::getTableName())
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
    public function getTolerablePeriodDisruption( $uid )
    {
        $TolerablePeriodDisruption = $this->TolerablePeriodDisruptionRepository->findbyUid($uid);

        if (!$TolerablePeriodDisruption) {
            throw new NotFoundException(__('activity::app.remote_access_factor.not_found'));
        }

        return $TolerablePeriodDisruption;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createTolerablePeriodDisruption( Request $request )
    {
        return $this->TolerablePeriodDisruptionRepository->create( $request->all() );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function updateTolerablePeriodDisruption( Request $request, $uid )
    {
        $TolerablePeriodDisruption = $this->getTolerablePeriodDisruption( $uid );

        return $this->TolerablePeriodDisruptionRepository->update( $request->all(), $TolerablePeriodDisruption->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteTolerablePeriodDisruption( $uid )
    {
        $TolerablePeriodDisruption = $this->getTolerablePeriodDisruption( $uid );

        return $this->TolerablePeriodDisruptionRepository->delete( $TolerablePeriodDisruption->id );
    }
}
