<?php

namespace Encoda\Activity\Services\Interfaces;

use Illuminate\Http\Request;

interface ApplicationServiceInterface
{

    public function listApplications();

    public function getApplication( $uid );

    public function createApplication( Request $request );

    public function updateApplication( Request $request, $uid);

    public function deleteApplication( $uid );
}
