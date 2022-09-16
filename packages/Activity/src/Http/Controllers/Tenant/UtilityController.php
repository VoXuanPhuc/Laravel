<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;
use Illuminate\Http\Request;

class UtilityController extends Controller
{


    public function __construct(
        protected UtilityServiceInterface $utilityService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->utilityService->listUtilities( $this->getTenant()->uid );
    }

    public function create( Request $request ) {

    }

    public function update( Request $request, $uid ) {

    }

    public function delete( $uid ) {

    }
}
