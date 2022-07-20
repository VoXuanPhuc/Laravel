<?php

namespace Encoda\Auth\Services;

use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;

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
     */
    public function authenticate( Request $request)
    {
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
}
