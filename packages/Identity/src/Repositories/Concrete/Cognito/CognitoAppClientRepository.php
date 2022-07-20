<?php

namespace Encoda\Identity\Repositories\Concrete\Cognito;

use Encoda\Identity\Models\Cognito\CognitoAppClient;
use Encoda\Identity\Repositories\Interfaces\ClientRepositoryInterface;
use Illuminate\Support\Facades\Event;

class CognitoAppClientRepository extends CognitoIdentityBaseRepository implements ClientRepositoryInterface
{

    public function model()
    {
        return CognitoAppClient::class;
    }

    public function create(array $attributes)
    {
        Event::dispatch( 'identity.client.create.before' );

        $client = $this->model->create( $attributes );

        Event::dispatch( 'identity.client.create.after' );
        return $client;
    }

    public function update(array $attributes, $id)
    {
        return parent::update($attributes, $id);
    }
}
