<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface RemoteAccessFactorServiceInterface
{
    public function listRemoteAccessFactors();

    public function getRemoteAccessFactor( $uid );

    public function createRemoteAccessFactor( Request $request );

    public function updateRemoteAccessFactor(Request $request, $uid);

    public function deleteRemoteAccessFactor( $uid );
}
