<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface TolerablePeriodDisruptionServiceInterface
{
    public function listTolerablePeriodDisruptions();

    public function getTolerablePeriodDisruption( $uid );

    public function createTolerablePeriodDisruption( Request $request );

    public function updateTolerablePeriodDisruption(Request $request, $uid);

    public function deleteTolerablePeriodDisruption( $uid );
}
