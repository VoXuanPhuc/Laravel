<?php

namespace Encoda\Identity\Services\Concrete\Cognito;

use Encoda\Identity\Http\Requests\Client\CreateClientRequest;
use Encoda\Identity\Http\Requests\UpdateClientRequest;
use Encoda\Identity\Repositories\Interfaces\ClientRepositoryInterface;
use Encoda\Identity\Services\Interfaces\AdminClientServiceInterface;

class IdentityCognitoAdminClientService implements AdminClientServiceInterface
{

    public ClientRepositoryInterface $clientRepository;

    public function __construct( ClientRepositoryInterface $clientRepository )
    {
        $this->clientRepository = $clientRepository;
    }

    public function listClients()
    {
        return $this->clientRepository->all();
    }

    public function createClient(CreateClientRequest $request)
    {
        return $this->clientRepository->create( $request->all() );
    }

    public function updateClient(UpdateClientRequest $request, $clientId )
    {
        $this->clientRepository->update( $request->all(), $clientId );
    }
}
