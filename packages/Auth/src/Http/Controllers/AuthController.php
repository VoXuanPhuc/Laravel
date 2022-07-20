<?php
namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Services\AuthService;
use Encoda\Core\Exceptions\BadRequestException;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct( AuthService $authService )
    {
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return mixed|null
     * @throws BadRequestException
     */
    public function login( Request $request ) {

        $response = $this->authService->authenticate( $request );

        return $response;
    }

    /**
     * @return mixed|null
     * @throws BadRequestException
     */
    public function getAccessToken(Request $request) {
        return $this->login($request);
    }

    /**
     * @param SignupRequest $signupRequest
     * @return \Aws\Result|false
     */
    public function signup( SignupRequest $signupRequest ) {

        $signupRequest = new SignupRequest();

        $response = $this->authService->signup( $signupRequest );

        return $response;
    }

    /**
     * @return string
     */
    public function profile() {
        return 'Profile';
    }
}
