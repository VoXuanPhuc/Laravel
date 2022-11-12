<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\TolerableTimePeriodServiceInterface;
use Illuminate\Http\Request;

class TolerableTimePeriodController extends Controller
{


    public function __construct(
        protected TolerableTimePeriodServiceInterface $accessFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->accessFactorService->listTolerableTimePeriods();
    }

  /**
     * @return mixed
     */
    public function all() {

        return $this->accessFactorService->listAllTolerableTimePeriods();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {
        return $this->accessFactorService->createTolerableTimePeriod( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->accessFactorService->updateTolerableTimePeriod( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->accessFactorService->deleteTolerablePeriod( $uid );
    }
}
