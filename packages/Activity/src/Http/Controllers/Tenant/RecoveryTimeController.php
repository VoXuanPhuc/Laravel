<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\RecoveryTimeServiceInterface;
use Illuminate\Http\Request;

class RecoveryTimeController extends Controller
{


    public function __construct(
        protected RecoveryTimeServiceInterface $accessFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->accessFactorService->listRecoveryTimes();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {
        return $this->accessFactorService->createRecoveryTime( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->accessFactorService->updateRecoveryTime( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->accessFactorService->deleteRecoveryTime( $uid );
    }
}
