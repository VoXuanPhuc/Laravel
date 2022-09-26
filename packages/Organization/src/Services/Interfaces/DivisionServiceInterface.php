<?php

namespace Encoda\Organization\Services\Interfaces;

use Encoda\Organization\Http\Requests\Division\CreateDivisionRequest;
use Encoda\Organization\Http\Requests\Division\UpdateDivisionRequest;

interface DivisionServiceInterface
{

    public function listDivision();
    public function getDivision( $uid );
    public function createDivision( CreateDivisionRequest $request );
    public function updateDivision( UpdateDivisionRequest $request, $uid );
    public function deleteDivision( $uid );
}
