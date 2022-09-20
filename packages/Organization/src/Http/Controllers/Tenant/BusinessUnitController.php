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

        return  $this->businessUnitService->listBusinessUnit( $this->getTenant()->uid, $divisionUid );
    }

    /**
     * @return mixed
     */
    public function businessUnitByOrg() {

        return  $this->businessUnitService->listBusinessUnitByOrg( $this->getTenant()->uid );
    }

    public function detail( $divisionUid, $uid ) {
        return $this->businessUnitService->getBusinessUnit( $this->getTenant()->uid, $divisionUid, $uid );
    }

    /**
     * @param CreateBusinessUnitRequest $request
     * @param $divisionUid
     * @return mixed
     */
    public function create( CreateBusinessUnitRequest $request, $divisionUid ) {
        return $this->businessUnitService->createBusinessUnit( $request, $this->getTenant()->uid, $divisionUid );
    }

    /**
     * @param UpdateBusinessUnitRequest $request
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function update( UpdateBusinessUnitRequest $request, $divisionUid, $uid ) {

        return $this->businessUnitService->updateBusinessUnit( $request, $this->getTenant()->uid, $divisionUid, $uid );
    }

    /**
     * @param $divisionUid
     * @param $uid
     * @return mixed
     */
    public function delete( $divisionUid, $uid ) {
        return $this->businessUnitService->deleteBusinessUnit( $this->getTenant()->uid, $divisionUid, $uid );
    }
}
