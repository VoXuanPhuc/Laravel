<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;
use Encoda\Organization\Models\BusinessUnit;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\Interfaces\BusinessUnitRepositoryInterface;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;

class BusinessUnitService implements BusinessUnitServiceInterface
{

    /**
     * @param DivisionServiceInterface $divisionService
     * @param BusinessUnitRepositoryInterface $businessUnitRepository
     */
    public function __construct(
        protected  DivisionServiceInterface $divisionService,
        protected BusinessUnitRepositoryInterface $businessUnitRepository
    )
    {
    }

    /**
     * @param $divisionUid
     *
     * @return mixed
     * @throws ValidationException
     */
    public function listBusinessUnit( $divisionUid )
    {
        $division = $this->divisionService->getDivision( $divisionUid );


        if( $division ) {
            $query = $division->business_units()->with('division');
            $this->applySearchFilter($query);
            $this->applySortFilter($query);
            return $this->businessUnitRepository->applyPaging($query);
        }

        return [];
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
            ->setTable(BusinessUnit::getTableName())
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
            ->setTable(BusinessUnit::getTableName())
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
    public function getBusinessUnit( $uid )
    {
        $businessUnit = $this->businessUnitRepository->findByUid( $uid );

        if( !$businessUnit ) {
            throw new NotFoundException( __('org::app.business_unit.not_found') );
        }

        return $businessUnit->load('division');
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function createBusinessUnit(CreateBusinessUnitRequest $request)
    {
        if(isset($request->get('division')['uid']) && $divisionUid = ($request->get('division')['uid'])){
            $division = $this->divisionService->getDivision( $divisionUid );

            $request->merge([
                'division_id' => $division->id,
            ]);
        }


        return $this->businessUnitRepository->create( $request->all() );
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $divisionUid
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     */
    public function updateBusinessUnit(UpdateBusinessUnitRequest $request, $uid)
    {
        $businessUnit = $this->getBusinessUnit( $uid );

        if(isset($request->get('division')['uid']) && $divisionUid = ($request->get('division')['uid'])){
            $division = $this->divisionService->getDivision( $divisionUid );
            $request->merge([
                'division_id' => $division->id,
            ]);
        }else{
            $request->merge([
                'division_id' => null,
            ]);
        }

        return $this->businessUnitRepository
                    ->update( $request->all(), $businessUnit->id )
                    ->load('division');
    }


    /**
     * @param $divisionUid
     * @param $uid
     * @return bool
     * @throws NotFoundException
     */
    public function deleteBusinessUnit($uid)
    {
        $businessUnit = $this->getBusinessUnit( $uid );

        if( $this->businessUnitRepository->delete( $businessUnit->id ) ) {
            return true;
        }

        return false;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function listBusinessUnitByOrg(): LengthAwarePaginator
    {
        return $this->businessUnitRepository
                    ->with('division')
                    ->paginate(config('config.pagination_size'));
    }

    /**
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getBusinessUnitWithoutDivision( $uid ): mixed
    {
        $businessUnit = $this->businessUnitRepository->findOneByField('uid', $uid );

        if( !$businessUnit ) {
            throw new NotFoundException( __('org::app.business_unit.not_found') );
        }

        return $businessUnit->load('division');
    }
}
