<?php

namespace Encoda\Auth\Services;

use Encoda\Auth\DTOs\TokenDTO;
use Encoda\Auth\Http\Requests\ChangePasswordRequest;
use Encoda\Auth\Interfaces\AuthChallengeServiceInterface;
use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Core\Exceptions\BadRequestException;

class AuthChallengeService implements AuthChallengeServiceInterface
{
    public function __construct(
        protected CognitoUserService $userService

    )
    {
    }

    /**
     * @param ChangePasswordRequest $request
     * @return TokenDTO
     * @throws BadRequestException
     */
    public function respondForceChangePasswordChallenge( ChangePasswordRequest $request ) {
        return $this->userService->respondForceChangePasswordChallenge(  $request );
    }
}
