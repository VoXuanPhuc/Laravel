<?php

namespace Encoda\Identity\Services\Concrete\Database;

use Encoda\Identity\Services\Interfaces\AdminServiceInterface;
use Illuminate\Http\Request;

class AdminService implements AdminServiceInterface
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
}
