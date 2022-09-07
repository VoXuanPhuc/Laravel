<?php

namespace Encoda\Organization\Http\Controllers\Admin;

use Encoda\Organization\Http\Controllers\Controller;
use Encoda\Organization\Http\Requests\Org\CreateOrganizationRequest;
use Encoda\Organization\Http\Requests\Org\UpdateOrganizationRequest;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    public function __construct( protected OrganizationServiceInterface $organizationService )
    {
    }

    /**
     * @return mixed
     */
    public function index() {
        return $this->organizationService->listOrganization();
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function detail( $uid ) {
        return $this->organizationService->getOrganization( $uid );

    }

    /**
     * @param CreateOrganizationRequest $request
     * @return mixed
     */
    public function create( CreateOrganizationRequest $request ) {

        return $this->organizationService->createOrganization( $request );
    }

    /**
     * @param UpdateOrganizationRequest $request
     * @param $uid
     * @return mixed
     */
    public function update( UpdateOrganizationRequest $request, $uid ) {
        return $this->organizationService->updateOrganization( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->organizationService->deleteOrganization( $uid );
    }

    public function uploadLogo( Request $request ) {
        return $this->organizationService->uploadLogo( $request );
    }
}
