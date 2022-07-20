<?php

namespace Encoda\Identity\Models\Cognito;

use Encoda\AWSCognito\Services\CognitoUserPoolClientService;
use Encoda\AWSCognito\DTO\UserPoolClient;
use Encoda\Identity\Contracts\ClientContract;

class CognitoAppClient extends CognitoBaseModel implements ClientContract
{

    protected CognitoUserPoolClientService $userPoolClientService;

    public function __construct( CognitoUserPoolClientService $userPoolClientService, array $attributes = [])
    {
        $this->userPoolClientService = $userPoolClientService;
        parent::__construct($attributes);
    }

    public function create($attributes)
    {
        $poolClient = new UserPoolClient(
            $attributes['clientName'],
        );

        //Call back URL here
        $poolClient->defaultRedirectURI = $this->getCallbackURLs( $attributes['clientName'] );
        $poolClient->callbackURLs = [  $this->getCallbackURLs( $attributes['clientName'] ) ];
        $poolClient->logoutURLs = [  $this->getLogoutURLs( $attributes['clientName'] ) ];


        return $this->userPoolClientService->createUserPoolClient( $poolClient );
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function list($columns = ['*'])
    {
        return $this->userPoolClientService->getListClient();
    }


    /**
     * @param $clientName
     * @return string
     */
    public function getCallbackURLs( $clientName ) {
        return config( 'cognito.callback_url') . $this->sanitizeClientName( $clientName );
    }


    public function getLogoutURLs( $clientName ) {
        return config( 'cognito.logout_url' ) . $this->sanitizeClientName( $clientName );
    }

    /**
     * @param $clientName
     * @return array|string|string[]|null
     */
    public function sanitizeClientName( $clientName ) {
        return strtolower( preg_replace(  "/[^A-Za-z0-9]/", '-', $clientName ) );
    }
}
