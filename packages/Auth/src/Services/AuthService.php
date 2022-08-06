<?php

namespace Encoda\Auth\Services;

use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{

    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $cognitoUserService )
    {
        $this->userService = $cognitoUserService;
    }


    /**
     * @param Request $request
     * @return \Aws\Result|false
     * @throws BadRequestException
     */
    public function authenticate( Request $request)
    {
        if( empty( $request->username ) || empty( $request->password ) ) {

            throw new BadRequestException( __('auth::app.login.credentials_are_required') );
        }

        return $this->getToken( $request->username, $request->password );
    }


    /**
     * @param $username
     * @param $password
     * @return \Aws\Result|false
     */
    protected function getToken( $username, $password )
    {
        return $this->userService->authenticate( $username, $password );
    }

    public function signup( CreateUserRequest $signupRequest )
    {
        return $this->userService->createUser( $signupRequest );
    }
}
