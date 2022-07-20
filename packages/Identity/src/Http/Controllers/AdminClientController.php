<?php

namespace Encoda\Identity\Http\Controllers;

use Encoda\Identity\Http\Requests\Client\CreateClientRequest;
use Encoda\Identity\Services\Interfaces\AdminClientServiceInterface;

class AdminClientController extends Controller
{

    private AdminClientServiceInterface $adminClientService;

    public function __construct( AdminClientServiceInterface $adminClientService )
    {
        $this->adminClientService = $adminClientService;
    }

    public function list() {
        return $this->adminClientService->listClients();
    }

    /**
     * @param CreateClientRequest $request
     * @return mixed
     */
    public function createClient(  CreateClientRequest $request) {

        return $this->adminClientService->createClient( $request );
    }
}
