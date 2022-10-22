<?php

namespace Encoda\Organization\Http\Controllers\Tenant;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;

class BusinessUnitController extends Controller
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected DivisionServiceInterface $divisionService,
        protected BusinessUnitServiceInterface $businessUnitService,
    )
    {
    }

    /**
     * @param $divisionUid
     * @return mixed
     */
    public function index( $divisionUid ) {

        return  $this->businessUnitService->listBusinessUnit(  $divisionUid );
    }

    /**
     * @return mixed
     */
    public function businessUnitByOrg(): mixed
    {

        return  $this->businessUnitService->listBusinessUnitByOrg();
    }

    /**
     * @param $uid
     *
     * @return mixed
     */
    public function detail( $uid ) {
        return $this->businessUnitService->getBusinessUnit( $uid );
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @param $divisionUid
     * @return mixed
     */
    public function create( CreateBusinessUnitRequest $request) {
        return $this->businessUnitService->createBusinessUnit( $request);
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function update( UpdateBusinessUnitRequest $request, $uid ) {

        return $this->businessUnitService->updateBusinessUnit( $request, $uid );
    }

    /**
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->businessUnitService->deleteBusinessUnit( $uid );
    }
}
