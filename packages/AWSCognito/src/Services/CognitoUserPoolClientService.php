<?php

namespace Encoda\AWSCognito\Services;

use Encoda\AWSCognito\DTO\UserPoolClient;

class CognitoUserPoolClientService extends CognitoBaseService
{

    /**
     * @param UserPoolClient $userPoolClient
     * @return mixed|null
     */
    public function createUserPoolClient( UserPoolClient $userPoolClient ) {

        $data = $userPoolClient->toArray();

        if( strlen( $userPoolClient->userPoolId ) <= 0 )
        {
            $data['UserPoolId'] = $this->cognitoClient->getUserPoolId();
        }

        $result = $this->cognitoClient->getClient()->createUserPoolClient( $data );

        return $result->get('UserPoolClient');
    }

    /**
     * @return mixed|null
     */
    public function getListClient()
    {
        $response = $this->cognitoClient->getClient()->listUserPoolClients(
            [
                'MaxResults' => 10,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('UserPoolClients');
    }

}
