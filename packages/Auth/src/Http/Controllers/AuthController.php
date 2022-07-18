<?php
namespace Encoda\Auth\Http\Controllers;

use Encoda\Auth\Http\Requests\SignupRequest;
use Encoda\Auth\Repositories\RoleRepository;
use Encoda\Auth\Services\UserService;
use Encoda\Core\Exceptions\BadRequestException;
use Laravel\Lumen\Http\Request;

class AuthController extends Controller
{

    protected UserService $userService;
    protected RoleRepository $roleRepository;

    /**
     * @param UserService $userService
     * @param RoleRepository $roleRepository
     */
    public function __construct( UserService $userService, RoleRepository $roleRepository )
    {
        $this->userService = $userService;
        $this->roleRepository = $roleRepository;
    }


    /**
     * @param Request $request
     * @return mixed|null
     * @throws BadRequestException
     */
    public function login( Request $request ) {

        $response = $this->userService->authenticate( $request );

        return $response->get('AuthenticationResult');
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

        $signupRequest->username = \request('username');
        $signupRequest->password = \request('password');
        $signupRequest->confirmPassword = \request('confirmPassword');
        $signupRequest->firstName = \request('firstName');
        $signupRequest->lastName = \request('lastName');

        $response = $this->userService->signup( $signupRequest );

        return $response;
    }

    /**
     * @return string
     */
    public function profile() {
        return 'Profile';
    }
}
