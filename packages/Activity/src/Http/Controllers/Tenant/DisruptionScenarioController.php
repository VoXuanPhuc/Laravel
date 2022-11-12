<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\DisruptionScenarioServiceInterface;
use Illuminate\Http\Request;

class DisruptionScenarioController extends Controller
{


    public function __construct(
        protected DisruptionScenarioServiceInterface $accessFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->accessFactorService->listDisruptionScenarios();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {
        return $this->accessFactorService->createDisruptionScenario( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->accessFactorService->updateDisruptionScenario( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->accessFactorService->deleteDisruptionScenario( $uid );
    }
}
