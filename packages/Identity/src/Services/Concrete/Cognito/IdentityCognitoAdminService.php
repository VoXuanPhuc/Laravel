<?php

namespace Encoda\Identity\Services\Concrete\Cognito;

use Encoda\AWSCognito\Client\AWSCognitoClient;
use Encoda\Identity\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\Request;

class IdentityCognitoAdminService implements AdminServiceInterface
{


    private AWSCognitoClient $cognitoClient;

    public function __construct(AWSCognitoClient $client )
    {
        $this->cognitoClient = $client;
    }

    public function disableUser( $username )
    {
        $response = $this->cognitoClient->getClient()->adminDisableUser(
            [
                'Username' => $username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('@metadata');
    }


    public function enableUser( $username )
    {
        $response = $this->cognitoClient->getClient()->adminEnableUser(
            [
                'Username' => $username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('@metadata');
    }

    public function resetUserPassword(Request $request, $username)
    {
        // TODO: Implement resetUserPassword() method.
    }


    public function listUserGroups($username)
    {
        $response = $this->cognitoClient->getClient()->adminListGroupsForUser(
            [
                'Username' => $username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('Groups');
    }

    /**
     * @param Request $request
     * @param $groupId
     * @return mixed|null
     */
    public function addUserToGroup(Request $request, $groupId )
    {
        $response = $this->cognitoClient->getClient()->adminAddUserToGroup(
            [
                'GroupName' => $groupId,
                'Username' => $request->username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('@metadata');
    }

    public function removeUserFromGroup(Request $request, $groupId)
    {
        $response = $this->cognitoClient->getClient()->adminRemoveUserFromGroup(
            [
                'GroupName' => $groupId,
                'Username' => $request->username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('@metadata');
    }

    /**
     * @param Request $request
     * @return \Aws\Result
     */
    public function confirmSignup(Request $request)
    {
        $response = $this->cognitoClient->getClient()->adminConfirmSignUp(
            [
                'Username' => $request->username,
                'UserPoolId' => $this->cognitoClient->getUserPoolId(),
            ]
        );

        return $response->get('@metadata');
    }
}
