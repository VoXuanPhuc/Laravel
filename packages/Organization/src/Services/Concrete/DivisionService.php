<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;
use Encoda\Organization\Models\Division;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\Interfaces\DivisionRepositoryInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class DivisionService implements DivisionServiceInterface
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected DivisionRepositoryInterface $divisionRepository
    )
    {

    }

    /**
     * @return LengthAwarePaginator
     */
    public function listDivision()
    {
        $query = $this->divisionRepository->query();
        $this->applySearchFilter($query);
        $this->applySortFilter($query);
        return $this->divisionRepository->applyPaging($query);
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
            ->setTable(Division::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'description', 'is_active'])
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
            ->setTable(Division::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'is_active'])
            ->validate()
            ->applySort();
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getDivision($uid)
    {

        $division = $this->divisionRepository->findByUid( $uid );

        if( !$division ) {
            throw new NotFoundException( __('org::app.division.not_found') );
        }

        return $division;
    }

    /**
     * @param CreateDivisionRequest $request
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function createDivision( CreateDivisionRequest $request )
    {
        return $this->divisionRepository->create( $request->all() );
    }

    /**
     * @param UpdateDivisionRequest $request
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     */
    public function updateDivision(UpdateDivisionRequest $request, $uid)
    {
        $division = $this->getDivision( $uid );

        return $this->divisionRepository->update( $request->all(), $division->id );
    }

    /**
     * @param $uid
     * @return int
     * @throws NotFoundException
     */
    public function deleteDivision( $uid)
    {
        $division =  $this->getDivision( $uid );
        return $this->divisionRepository->delete( $division->id );
    }
}
