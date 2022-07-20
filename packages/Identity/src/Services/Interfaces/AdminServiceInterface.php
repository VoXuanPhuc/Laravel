<?php

namespace Encoda\Identity\Services\Interfaces;

use Illuminate\Http\Request;

interface AdminServiceInterface
{

    public function disableUser( $username );

    public function enableUser( $username );

    public function resetUserPassword( Request $request, $username );

    public function listUserGroups( $username );

    public function addUserToGroup( Request $request, $groupId );

    public function removeUserFromGroup(Request $request, $groupId);

    public function confirmSignup(Request $request);
}
