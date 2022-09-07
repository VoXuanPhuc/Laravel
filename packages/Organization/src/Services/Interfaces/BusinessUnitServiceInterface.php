<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;

interface BusinessUnitServiceInterface
{


    public function listBusinessUnit( $organizationUid, $divisionUid );
    public function listBusinessUnitByOrg( $organizationUid );
    public function getBusinessUnit( $organizationUid, $divisionUid, $uid );
    public function createBusinessUnit( CreateBusinessUnitRequest $request, $organizationUid, $divisionUid );
    public function updateBusinessUnit( UpdateBusinessUnitRequest $request, $organizationUid, $divisionUid, $uid );
    public function deleteBusinessUnit( $organizationUid, $divisionUid, $uid );
}
