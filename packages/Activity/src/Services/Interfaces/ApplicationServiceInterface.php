<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface ApplicationServiceInterface
{

    public function listApplications($organizationUid );

    public function getApplication($organizationUid, $uid);

    public function createApplication(Request $request, $organizationUid );

    public function updateApplication(Request $request, $organizationUid, $uid);

    public function deleteApplication($organizationUid, $uid);
}
