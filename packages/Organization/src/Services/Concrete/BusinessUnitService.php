<?php

namespace Encoda\Organization\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Repositories\BusinessUnitRepository;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class BusinessUnitService implements BusinessUnitServiceInterface
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected  DivisionServiceInterface $divisionService,
        protected BusinessUnitRepository $businessUnitRepository
    )
    {
    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @return mixed
     */
    public function listBusinessUnit( $organizationUid, $divisionUid )
    {
        $division = $this->divisionService->getDivision( $organizationUid, $divisionUid );


        if( $division ) {
            return $division->business_units()->with('division')->paginate(config('config.pagination_size'));
        }

        return [];
    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return mixed
     * @throws NotFoundException
     */
    public function getBusinessUnit($organizationUid, $divisionUid, $uid)
    {
        $division = $this->divisionService->getDivision( $organizationUid, $divisionUid );

        $businessUnit = $this->businessUnitRepository->findOneWhere(
           [
               ['division_id', '=', $division->id],
               ['uid', '=', $uid]
           ]
        );

        if( !$businessUnit ) {
            throw new NotFoundException( __('org::app.business_unit.not_found') );
        }

        return $businessUnit->load('division');
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws ValidatorException
     */
    public function createBusinessUnit(CreateBusinessUnitRequest $request, $organizationUid, $divisionUid )
    {
        $organization = $this->organizationService->getOrganization( $organizationUid );
        $division = $this->divisionService->getDivision( $organizationUid, $divisionUid );

        $request->merge([
            'organization_id' => $organization->id,
            'division_id' => $division->id,
        ]);

        return $this->businessUnitRepository->create( $request->all() );
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return LengthAwarePaginator|Collection|mixed
     * @throws NotFoundException
     * @throws ValidatorException
     */
    public function updateBusinessUnit(UpdateBusinessUnitRequest $request, $organizationUid, $divisionUid, $uid)
    {
        $businessUnit = $this->getBusinessUnit( $organizationUid, $divisionUid, $uid );

        if( $divisionUid != $request->division['uid'] ) {
            $division = $this->divisionService->getDivision( $organizationUid, $request->division['uid'] );

            $request->merge([
                'division_id' => $division->id,
            ]);
        }

        return $this->businessUnitRepository->update( $request->all(), $businessUnit->id )
            ->load('division');
    }


    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return bool
     * @throws NotFoundException
     */
    public function deleteBusinessUnit($organizationUid, $divisionUid,$uid)
    {
        $businessUnit = $this->getBusinessUnit( $organizationUid, $divisionUid, $uid );

        if( $this->businessUnitRepository->delete( $businessUnit->id ) ) {
            return true;
        }

        return false;
    }

    /**
     * @param $organizationUid
     * @return LengthAwarePaginator
     */
    public function listBusinessUnitByOrg( $organizationUid )
    {
        /** @var Organization $organization */
        $organization = $this->organizationService->getOrganization( $organizationUid );

        return $organization->business_units()->with('division')->paginate(config('config.pagination_size'));
    }
}
