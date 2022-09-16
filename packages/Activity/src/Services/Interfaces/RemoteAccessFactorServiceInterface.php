<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface RemoteAccessFactorServiceInterface
{
    public function listRemoteAccessFactors($organizationUid );

    public function getRemoteAccessFactor($organizationUid, $uid);

    public function createRemoteAccessFactor(Request $request, $organizationUid );

    public function updateRemoteAccessFactor(Request $request, $organizationUid, $uid);

    public function deleteRemoteAccessFactor($organizationUid, $uid);
}
