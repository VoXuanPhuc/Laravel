<?php

namespace Encoda\Identity\Services\Interfaces;

use Encoda\Identity\Http\Requests\Admin\AdminCreateUserRequest;
use Illuminate\Http\Request;

interface AdminUserServiceInterface
{

    public function adminGetUser( $username );

    public function adminCreateUser( AdminCreateUserRequest $request );

    public function adminUpdateUser( $username, array $data = [] );

    public function disableUser( $username );

    public function enableUser( $username );

    public function resetUserPassword( Request $request, $username );

    public function addUserToGroup( Request $request, $groupId );

    public function removeUserFromGroup(Request $request, $groupId);

    public function confirmSignup(Request $request);

    public function deleteUser($id);
}
