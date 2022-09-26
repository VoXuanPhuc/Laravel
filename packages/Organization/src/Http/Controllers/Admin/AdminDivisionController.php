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
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ) {

        return $this->divisionService->getDivision( $uid );
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
     * @param $uid
     * @return mixed
     */
    public function update( UpdateDivisionRequest $request, $uid ) {
        return $this->divisionService->updateDivision( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ) {
        return $this->divisionService->deleteDivision( $uid );
    }
}
