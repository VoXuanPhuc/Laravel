<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;

interface DivisionServiceInterface
{

    public function listDivision( $organizationUid );
    public function getDivision( $organizationUid, $uid );
    public function createDivision( CreateDivisionRequest $request, $organizationUid );
    public function updateDivision( UpdateDivisionRequest $request, $organizationUid, $uid );
    public function deleteDivision( $organizationUid, $uid );
}
