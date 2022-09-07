<?php

namespace Encoda\Organization\Http\Controllers\Admin;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;

class AdminDivisionController extends Controller
{

    public function __construct(
        protected OrganizationServiceInterface $organizationService,
        protected DivisionServiceInterface $divisionService,
    )
    {
    }

    /**
     * @return mixed
     */
    public function index( $organizationUid ) {

        return $this->divisionService->listDivision( $organizationUid );
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     */
    public function detail( $organizationUid, $uid ) {

        return $this->divisionService->getDivision( $organizationUid, $uid );
    }

    /**
     * @param CreateDivisionRequest $request
     * @param $organizationUid
     * @return mixed
     */
    public function create( CreateDivisionRequest $request, $organizationUid ) {

        return $this->divisionService->createDivision( $request, $organizationUid );
    }

    /**
     * @param UpdateDivisionRequest $request
     * @param $organizationUid
     * @param $uid
     * @return mixed
     */
    public function update( UpdateDivisionRequest $request, $organizationUid, $uid ) {
        return $this->divisionService->updateDivision( $request, $organizationUid, $uid );
    }

    /**
     * @param $organizationUid
     * @param $uid
     * @return mixed
     */
    public function delete ( $organizationUid, $uid ) {
        return $this->divisionService->deleteDivision( $organizationUid, $uid );
    }
}
