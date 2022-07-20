<?php

namespace Encoda\Identity\Services\Interfaces;

use Encoda\Identity\Http\Requests\UserGroup\CreateUserGroupRequest;
use Encoda\Identity\Http\Requests\UserGroup\UpdateUserGroupRequest;

interface UserGroupServiceInterface
{

    public function listGroup();
    public function createGroup( CreateUserGroupRequest $request );
    public function updateGroup( UpdateUserGroupRequest $request, $id );
    public function getGroup($id);
    public function deleteGroup( $id );
}
