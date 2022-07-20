<?php
namespace Encoda\AWSCognito\Client;


use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class AWSCognitoClient
{



    /**
     * @var CognitoIdentityProviderClient
     */
    protected CognitoIdentityProviderClient $client;

    /**
     * @var
     */
    public $clientId;

    /**
     * @var
     */
    public $clientSecret;

    /**
     * @var
     */
    public $userPoolId;

    public function __construct(CognitoIdentityProviderClient $client, $clientId, $clientSecret, $poolId )
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->userPoolId = $poolId;
    }


    /**
     * @return CognitoIdentityProviderClient
     */
    public function getClient(): CognitoIdentityProviderClient
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return mixed
     */
    public function getUserPoolId()
    {
        return $this->userPoolId;
    }
}
