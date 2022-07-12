<?php
namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Http\Requests\SignupRequest;
use Encoda\Auth\Services\UserService;
use Laravel\Lumen\Http\Request;

class AuthController extends Controller
{

    protected UserService $userService;

    public function __construct( UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @param Request $request
     * @return mixed|null
     */
    public function login( Request $request ) {

        $response = $this->userService->authenticate( $request );

        return $response->get('AuthenticationResult');
    }

    /**
     * @return mixed|null
     */
    public function getAccessToken(Request $request) {
        return $this->login($request);
    }

    /**
     * @param SignupRequest $signupRequest
     * @return \Aws\Result|false
     */
    public function signup( SignupRequest $signupRequest ) {

        return $this->userService->signup( $signupRequest );

    }

    /**
     * @return string
     */
    public function profile() {
        return 'Profile';
    }
}
