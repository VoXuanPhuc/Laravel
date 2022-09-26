<?php

namespace Encoda\AWSCognito\Services;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Encoda\AWSCognito\Client\AWSCognitoClient;

class CognitoBaseService
{
    /**
     * @var AWSCognitoClient
     */
    protected AWSCognitoClient $cognitoClient;

    /**
     * @param AWSCognitoClient $client
     */
    public function __construct( AWSCognitoClient $client )
    {
        $this->cognitoClient = $client;
    }

    /** ======= HELPERS ======= */
    /**
     * @param $username
     * @param array $attributes
     * @return bool
     */
    public function setUserAttributes($username, array $attributes)
    {
        $this->cognitoClient->getClient()->AdminUpdateUserAttributes([
            'Username' => $username,
            'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            'UserAttributes' => $this->formatAttributes($attributes),
        ]);

        return true;
    }

    /**
     * Format attributes in Name/Value array
     * @param array $attributes
     * @return array
     */
    protected function formatAttributes(array $attributes)
    {
        $userAttributes = [];

        foreach ($attributes as $key => $value) {
            $userAttributes[] = [
                'Name' => $key,
                'Value' => $value,
            ];
        }

        return $userAttributes;
    }

    /**
     * @param $username
     * @return string
     */
    public function cognitoSecretHash($username)
    {
        return $this->hash( $username . $this->cognitoClient->getClientId() );
    }

    /**
     * @param $message
     * @return string
     */
    protected function hash($message)
    {
        $hash = hash_hmac(
            'sha256',
            $message,
            $this->cognitoClient->getClientSecret(),
            true
        );

        return base64_encode($hash);
    }

}
