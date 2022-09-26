<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;
use Encoda\Organization\Repositories\Interfaces\BusinessUnitRepositoryInterface;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
     * @return mixed
     */
    public function listBusinessUnit( $divisionUid )
    {
        $division = $this->divisionService->getDivision( $divisionUid );


        if( $division ) {
            return $division->business_units()->with('division')->paginate(config('config.pagination_size'));
        }

        return [];
    }

    /**
     * @param $divisionUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getBusinessUnit( $divisionUid, $uid)
    {
        $businessUnit = $this->businessUnitRepository->findByUid( $uid );

        if( !$businessUnit ) {
            throw new NotFoundException( __('org::app.business_unit.not_found') );
        }

        return $businessUnit->load('division');
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @param $divisionUid
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function createBusinessUnit(CreateBusinessUnitRequest $request, $divisionUid )
    {
        $division = $this->divisionService->getDivision( $divisionUid );

        $request->merge([
            'division_id' => $division->id,
        ]);

        return $this->businessUnitRepository->create( $request->all() );
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $divisionUid
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     */
    public function updateBusinessUnit(UpdateBusinessUnitRequest $request, $divisionUid, $uid)
    {
        $businessUnit = $this->getBusinessUnit( $divisionUid, $uid );

        if( $divisionUid != $request->division['uid'] ) {
            $division = $this->divisionService->getDivision( $request->division['uid'] );

            $request->merge([
                'division_id' => $division->id,
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
    public function deleteBusinessUnit( $divisionUid,$uid)
    {
        $businessUnit = $this->getBusinessUnit( $divisionUid, $uid );

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
