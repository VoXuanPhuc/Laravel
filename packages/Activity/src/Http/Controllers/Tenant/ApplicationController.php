<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\ApplicationServiceInterface;

class ApplicationController extends Controller
{


    public function __construct( protected ApplicationServiceInterface $applicationService )
    {
    }

    /**
     * @return mixed
     */
    public function index() {
        return $this->applicationService->listApplications( $this->getTenant()->uid );
    }
}
