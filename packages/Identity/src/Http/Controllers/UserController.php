<?php

namespace Encoda\Identity\Http\Controllers;

use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Http\Requests\User\UpdateUserRequest;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected AdminUserServiceInterface $adminService;
    protected UserServiceInterface $userService;

    /**
     * @param UserServiceInterface $userService
     */
    public function __construct( UserServiceInterface $userService )
    {
        $this->userService = $userService;
    }


    /**
     * @return UserContract[]
     */
    public function list() {
        return $this->userService->listUsers();
    }


    /**
     * @param CreateUserRequest $request
     * @return UserContract
     */
    public function create(CreateUserRequest $request ) {

        return $this->userService->createUser( $request );
    }


    /**
     * @param UpdateUserRequest $request
     * @param $id
     * @return mixed
     */
    public function update( UpdateUserRequest $request, $id ) {
        return $this->userService->updateUser( $request, $id );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function detail( $id ) {
        $user = $this->userService->getUser( $id );

        return $user;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete( $id ) {
        return $this->userService->deleteUser( $id );
    }

    public function confirmSignup( Request $request ) {
        return $this->userService->confirmSignup( $request );
    }
}
