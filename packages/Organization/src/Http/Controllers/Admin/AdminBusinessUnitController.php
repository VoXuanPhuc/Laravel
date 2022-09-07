<?php

namespace Encoda\Organization\Http\Controllers\Admin;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;

class AdminBusinessUnitController extends Controller
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected DivisionServiceInterface $divisionService,
        protected BusinessUnitServiceInterface $businessUnitService,

    )
    {

    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @return array
     */
    public function index( $organizationUid, $divisionUid ) {

       return $this->businessUnitService->listBusinessUnit( $organizationUid, $divisionUid );

    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function detail( $organizationUid, $divisionUid, $uid ) {

        return $this->businessUnitService->getBusinessUnit( $organizationUid, $divisionUid, $uid);
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @return mixed
     */
    public function create( CreateBusinessUnitRequest $request, $organizationUid, $divisionUid ) {

        return $this->businessUnitService->createBusinessUnit( $request, $organizationUid, $divisionUid );
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function update( UpdateBusinessUnitRequest $request, $organizationUid, $divisionUid, $uid ) {
        return $this->businessUnitService->updateBusinessUnit( $request, $organizationUid, $divisionUid, $uid );
    }

    /**
     * @param $organizationUid
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function delete ( $organizationUid, $divisionUid, $uid ) {
        return $this->businessUnitService->deleteBusinessUnit( $organizationUid, $divisionUid, $uid );
    }

    /**
     * @param $organizationUid
     * @return mixed
     */
    public function getOrgBusinessUnits( $organizationUid ) {
        return $this->businessUnitService->listBusinessUnitByOrg( $organizationUid );
    }
}
