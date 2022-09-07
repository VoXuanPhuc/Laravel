<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\Org\CreateOrganizationRequest;
use Encoda\Organization\Http\Requests\Org\UpdateOrganizationRequest;
use Laravel\Lumen\Http\Request;

interface OrganizationServiceInterface
{

    public function listOrganization();
    public function getOrganization( $uid );
    public function createOrganization( CreateOrganizationRequest $request );
    public function updateOrganization( UpdateOrganizationRequest $request, $uid );
    public function deleteOrganization( $uid );

}
