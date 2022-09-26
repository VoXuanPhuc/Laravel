<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\Auth\Http\Requests\ChangePasswordRequest;
use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Core\Exceptions\BadRequestException;
use Illuminate\Http\Request;

class CognitoAuthService implements AuthServiceInterface
{

    public function __construct( protected CognitoUserService $cognitoUserService )
    {

    }

    /**
     * @throws UserNotFoundException
     */
    public function getToken($username, $password)
    {
        return $this->cognitoUserService->authenticate( $username, $password );
    }

    /**
     * @throws UserNotFoundException|BadRequestException
     */
    public function authenticate(Request $request)
    {

        if( empty( $request->username ) || empty( $request->password ) ) {

            throw new BadRequestException( __('auth::app.login.credentials_are_required') );
        }

        return $this->getToken( $request->username, $request->password );
    }

    /**
     * @param ChangePasswordRequest $request
     * @return array|void
     * @throws BadRequestException
     */
    public function respondForceChangePasswordChallenge(ChangePasswordRequest $request)
    {
        return $this->cognitoUserService->respondForceChangePasswordChallenge( $request->all() );
    }
}
