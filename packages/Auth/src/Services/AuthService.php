<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\Exceptions\UnauthorizedException;
use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Models\Database\User;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService implements AuthServiceInterface
{


    /**
     * @param $username
     * @param $password
     * @return bool
     * @throws UnauthorizedException
     */
    public function getToken($username, $password)
    {

        $credentials = [
            'email' => $username,
            'password' => $password,
        ];

        if( !$token = auth()->attempt( $credentials ) ) {
            throw new UnauthorizedException();
        }

        return $token;
    }

    /**
     * @param Request $request
     * @return array
     * @throws UnauthorizedException
     */
    public function authenticate(Request $request)
    {
        $token = $this->getToken( $request->get('username'), $request->get('password'));

        return $this->tokenData( $token );
    }


    /**
     * @param $token
     * @return array
     */
    protected function tokenData( $token ) {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    /**
     * @param Request $request
     */
    public function respondForceChangePasswordChallenge(\Illuminate\Http\Request $request)
    {
        // TODO: Implement changePassword() method.
    }
}
