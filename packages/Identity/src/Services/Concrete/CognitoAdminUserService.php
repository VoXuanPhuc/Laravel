<?php

namespace Encoda\Identity\Services\Concrete;

use Encoda\Identity\Http\Requests\Admin\AdminCreateUserRequest;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Illuminate\Http\Request;
use Encoda\AWSCognito\Services\CognitoAdminService as AWSCognitoAdminService;

class CognitoAdminUserService implements AdminUserServiceInterface
{


    public function __construct( protected  AWSCognitoAdminService $awsCognitoAdminService )
    {
    }

    /**
     * @param $username
     */
    public function adminGetUser($username)
    {

    }

    /**
     * @param AdminCreateUserRequest $request
     * @return mixed|null
     */
    public function adminCreateUser( AdminCreateUserRequest $request )
    {
        return $this->awsCognitoAdminService->adminCreateUser( $request->all() );
    }

    /**
     * @param $username
     * @param array $data
     */
    public function adminUpdateUser($username, array $data = [])
    {

    }

    /**
     * @param $usernames
     */
    public function disableUser($username)
    {

    }

    /**
     * @param $username
     */
    public function enableUser($username)
    {

    }

    /**
     * @param Request $request
     * @param $username
     */
    public function resetUserPassword(Request $request, $username)
    {

    }

    /**
     * @param Request $request
     * @param $groupId
     */
    public function addUserToGroup(Request $request, $groupId)
    {
    }

    /**
     * @param Request $request
     * @param $groupId
     */
    public function removeUserFromGroup(Request $request, $groupId)
    {
    }

    /**
     * @param Request $request
     */
    public function confirmSignup(Request $request)
    {
    }

    public function deleteUser($id)
    {
        $this->awsCognitoAdminService->adminDeleteUser( $id );
    }
}
