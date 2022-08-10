<?php

namespace Encoda\Identity\Services\Interfaces;

use Encoda\Identity\Http\Requests\User\CreateUserRequest;

interface UserServiceInterface
{

    public function deleteUser($id);

    public function getUser($id);

    public function listUsers();

    public function createUser( CreateUserRequest $request);

    public function confirmSignup(\Illuminate\Http\Request $request);

    public function authenticate($username, $password);

}
