<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface RecoveryTimeServiceInterface
{
    public function listRecoveryTimes();

    public function getRecoveryTime( $uid );

    public function createRecoveryTime( Request $request );

    public function updateRecoveryTime(Request $request, $uid);

    public function deleteRecoveryTime( $uid );
}
