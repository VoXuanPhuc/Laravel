<?php
namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected AuthServiceInterface $authService;

    /**
     * @param AuthServiceInterface $authService
     */
    public function __construct( AuthServiceInterface $authService )
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    public function login( Request $request ) {

        return $this->authService->authenticate( $request );

    }

    /**
     * @return mixed|null
     */
    public function getAccessToken(Request $request) {


        return $this->login($request);
    }


    public function signup( CreateUserRequest $signupRequest ) {
        // TODO: Allow users to signup new account
    }

    /**
     * @return string
     */
    public function profile() {
        return 'Profile';
    }
}
