<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface UtilityServiceInterface
{
    public function listUtilities($organizationUid );

    public function getUtility($organizationUid, $uid);

    public function createUtility(Request $request, $organizationUid );

    public function updateUtility(Request $request, $organizationUid, $uid);

    public function deleteUtility($organizationUid, $uid);
}
