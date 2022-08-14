<?php

namespace Encoda\Identity\Http\Controllers;

use Encoda\Identity\Http\Requests\Admin\AdminCreateUserRequest;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    private AdminUserServiceInterface $adminService;

    public function __construct(AdminUserServiceInterface $adminService )
    {
        $this->adminService = $adminService;
    }


    /**
     * @param AdminCreateUserRequest $request
     * @return mixed
     */
    public function createUser( AdminCreateUserRequest $request ) {
        return $this->adminService->adminCreateUser( $request );
    }

    /**
     * @return mixed
     */
    public function deleteUser( $id ) {
        return $this->adminService->deleteUser( $id );
    }

    /**
     * @param Request $request
     */
    public function disableUser( Request $request ) {

        return $this->adminService->disableUser( $request->username );
    }

    /**
     * @param Request $request
     */
    public function enableUser( Request $request ) {

        return $this->adminService->enableUser( $request->username );
    }

    public function listUserGroups( $username ) {

        return $this->adminService->listUserGroups( $username );
    }

    public function addUserToGroup( Request $request, $groupId ) {

        return $this->adminService->addUserToGroup( $request, $groupId );
    }

    /**
     * @param Request $request
     * @param $groupId
     * @return mixed
     */
    public function removeUserFromGroup( Request $request, $groupId ) {

        return $this->adminService->removeUserFromGroup( $request, $groupId );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function confirmSignup( Request $request ) {
        return $this->adminService->confirmSignup( $request );
    }

}
