<?php

namespace Encoda\Organization\Http\Controllers\Tenant;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;

class DivisionController extends Controller
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
    public function index() {

        return $this->divisionService->listDivision( $this->getTenant()->uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ) {

        return $this->divisionService->getDivision( $this->getTenant()->uid, $uid );
    }

    /**
     * @param CreateDivisionRequest $request
     * @return mixed
     */
    public function create( CreateDivisionRequest $request ) {

        return $this->divisionService->createDivision( $request, $this->getTenant()->uid );
    }

    /**
     * @param UpdateDivisionRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateDivisionRequest $request, $uid ) {
        return $this->divisionService->updateDivision( $request, $this->getTenant()->uid, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete ( $uid ) {

        return $this->divisionService->deleteDivision( $this->getTenant()->uid, $uid );
    }


}
