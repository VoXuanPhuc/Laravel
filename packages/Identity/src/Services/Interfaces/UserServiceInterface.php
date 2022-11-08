<?php

namespace Encoda\Identity\Services\Interfaces;

use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Http\Requests\User\ReinviteUserRequest;

interface UserServiceInterface
{

    public function deleteUser($id);

    public function getUser($id);

    public function listUsers();

    public function createUser( CreateUserRequest $request);

    public function confirmSignup(\Illuminate\Http\Request $request);

    public function authenticate($username, $password);

    public function reinviteUser(string $id);

}
