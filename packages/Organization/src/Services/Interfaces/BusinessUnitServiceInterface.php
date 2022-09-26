<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\BusinessUnit\CreateBusinessUnitRequest;
use Encoda\Organization\Http\Requests\BusinessUnit\UpdateBusinessUnitRequest;

interface BusinessUnitServiceInterface
{


    public function listBusinessUnit( $divisionUid );
    public function listBusinessUnitByOrg();
    public function getBusinessUnit( $divisionUid, $uid );
    public function getBusinessUnitWithoutDivision(  $uid );
    public function createBusinessUnit( CreateBusinessUnitRequest $request, $divisionUid );
    public function updateBusinessUnit( UpdateBusinessUnitRequest $request, $divisionUid, $uid );
    public function deleteBusinessUnit( $divisionUid, $uid );
}
