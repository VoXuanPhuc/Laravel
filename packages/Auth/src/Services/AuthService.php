<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService implements AuthServiceInterface
{


    public function getToken($username, $password)
    {
        // TODO: Implement getToken() method.
    }

    public function authenticate(\Illuminate\Http\Request $request)
    {
        // TODO: Implement authenticate() method.
    }
}
