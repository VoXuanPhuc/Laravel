<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\DTOs\AuthChallengeDTO;
use Encoda\Auth\DTOs\TokenDTO;
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
     * @return AuthChallengeDTO|TokenDTO
     * @throws UnauthorizedException|BadRequestException
     */
    public function authenticate( Request $request )
    {
        if( empty( $request->username ) || empty( $request->password ) ) {

            throw new BadRequestException( __('auth::app.login.credentials_are_required') );
        }

        $token = $this->getToken( $request->username, $request->password );

        return $this->tokenData( $token );
    }


    /**
     * @param $token
     * @return AuthChallengeDTO|TokenDTO
     */
    protected function tokenData( $token ) {

        // Should be from Cognito
        if( $token instanceof TokenDTO || $token instanceof AuthChallengeDTO ) {
            return $token;
        }

        return ( new TokenDTO() )
            ->accessToken( $token )
            ->expiresIn(
                auth()->factory()->getTTL() * 60
            );
    }
}
