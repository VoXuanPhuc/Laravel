<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface TolerableTimePeriodServiceInterface
{
    public function listAllTolerableTimePeriods();

    public function listTolerableTimePeriods();

    public function getTolerableTimePeriod($uid );

    public function createTolerableTimePeriod(Request $request );

    public function updateTolerableTimePeriod(Request $request, $uid);

    public function deleteTolerablePeriod($uid );
}
