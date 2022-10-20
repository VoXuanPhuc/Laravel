<?php
namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Http\Requests\ChangePasswordRequest;
use Encoda\Auth\Interfaces\AuthChallengeServiceInterface;
use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Illuminate\Http\Request;

class AuthChallengeController extends Controller
{

    protected AuthChallengeServiceInterface $authChallengeService;

    /**
     * @param AuthChallengeServiceInterface $authChallengeService
     */
    public function __construct( AuthChallengeServiceInterface $authChallengeService )
    {
        $this->authChallengeService = $authChallengeService;
    }


    /**
     * @param ChangePasswordRequest $request
     * @return mixed
     */
    public function respondForceChangePasswordChallenge( ChangePasswordRequest $request ): mixed
    {
        return $this->authChallengeService->respondForceChangePasswordChallenge( $request );
    }

}
