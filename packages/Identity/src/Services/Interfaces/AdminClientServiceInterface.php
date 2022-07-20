<?php

namespace Encoda\Identity\Services\Interfaces;

use Encoda\Identity\Http\Requests\Client\CreateClientRequest;
use Encoda\Identity\Http\Requests\UpdateClientRequest;

interface AdminClientServiceInterface
{

    public function listClients();
    public function createClient( CreateClientRequest $request );
    public  function updateClient( UpdateClientRequest $request, $clientId );
}
