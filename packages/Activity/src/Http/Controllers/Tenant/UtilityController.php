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

        return $this->utilityService->listUtilities();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {

        return $this->utilityService->createUtility( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->utilityService->updateUtility( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->utilityService->deleteUtility( $uid );
    }
}
