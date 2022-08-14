<?php

namespace Encoda\Identity\Services\Concrete;

use Encoda\Identity\Http\Requests\Admin\AdminCreateUserRequest;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Illuminate\Http\Request;

class AdminUserService implements AdminUserServiceInterface
{


    public function disableUser($username)
    {
        // TODO: Implement disableUser() method.
    }

    public function enableUser($username)
    {
        // TODO: Implement enableUser() method.
    }

    public function resetUserPassword(Request $request, $username)
    {
        // TODO: Implement resetUserPassword() method.
    }

    public function listUserGroups($username)
    {
        // TODO: Implement listUserGroups() method.
    }

    public function addUserToGroup(Request $request, $groupId)
    {
        // TODO: Implement addUserToGroup() method.
    }

    public function removeUserFromGroup(Request $request, $groupId)
    {
        // TODO: Implement removeUserFromGroup() method.
    }

    public function confirmSignup(Request $request)
    {
        // TODO: Implement configmSignup() method.
    }

    public function adminGetUser($username)
    {
        // TODO: Implement adminGetUser() method.
    }

    public function adminCreateUser( AdminCreateUserRequest $request )
    {
        // TODO: Implement adminCreateUser() method.
    }

    public function adminUpdateUser( $username, array $data = [] )
    {
        // TODO: Implement adminUpdateUser() method.
    }

    public function deleteUser($id)
    {
        // TODO: Implement deleteUser() method.
    }
}
