<?php

namespace Encoda\Activity\Http\Controllers\Tenant;

use Encoda\Activity\Http\Controllers\Controller;
use Encoda\Activity\Services\Interfaces\RemoteAccessFactorServiceInterface;
use Illuminate\Http\Request;

class RemoteAccessFactorController extends Controller
{


    public function __construct(
        protected RemoteAccessFactorServiceInterface $accessFactorService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->accessFactorService->listRemoteAccessFactors();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create( Request $request ) {
        return $this->accessFactorService->createRemoteAccessFactor( $request );
    }

    /**
     * @param Request $request
     * @param $uid
     * @return mixed
     */
    public function update( Request $request, $uid ) {
        return $this->accessFactorService->updateRemoteAccessFactor( $request, $uid );
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function delete( $uid ) {
        return $this->accessFactorService->deleteRemoteAccessFactor( $uid );
    }
}
