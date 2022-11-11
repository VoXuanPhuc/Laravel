<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\TolerablePeriodDisruptionServiceInterface;
use Illuminate\Http\Request;

class TolerablePeriodDisruptionController extends Controller
{


    public function __construct(
        protected TolerablePeriodDisruptionServiceInterface $accessFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->accessFactorService->listTolerablePeriodDisruptions();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {
        return $this->accessFactorService->createTolerablePeriodDisruption( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->accessFactorService->updateTolerablePeriodDisruption( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->accessFactorService->deleteTolerablePeriodDisruption( $uid );
    }
}
