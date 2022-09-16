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

        return $this->accessFactorService->listRemoteAccessFactors( $this->getTenant()->uid );
    }

    public function create( Request $request ) {

    }

    public function update( Request $request, $uid ) {

    }

    public function delete( $uid ) {

    }
}
