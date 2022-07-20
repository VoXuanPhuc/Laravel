<?php

namespace Encoda\Identity\Http\Controllers;

use Aws\Result;
use Encoda\Identity\Http\Requests\UserGroup\CreateUserGroupRequest;
use Encoda\Identity\Http\Requests\UserGroup\UpdateUserGroupRequest;
use Encoda\Identity\Services\Interfaces\UserGroupServiceInterface;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{

    protected UserGroupServiceInterface $userGroupService;

    /**
     * @param UserGroupServiceInterface $groupService
     */
    public function __construct( UserGroupServiceInterface $groupService )
    {
        $this->userGroupService = $groupService;
    }

    public function list() {
        return $this->userGroupService->listGroup();
    }

    /**
     * @param CreateUserGroupRequest $request
     * @return Result
     */
    public function create( CreateUserGroupRequest $request ) {

        return $this->userGroupService->createGroup( $request );
    }


    /**
     * @param UpdateUserGroupRequest $request
     * @param $id
     * @return mixed
     */
    public function update( UpdateUserGroupRequest $request, $id ) {
        return $this->userGroupService->updateGroup( $request, $id );
    }

    /**
     * @param $id
     * @return Result
     */
    public function detail( $id ) {
        return $this->userGroupService->getGroup( $id );
    }

    /**
     * @param $id
     * @return Result
     */
    public function delete( $id ) {
        return $this->userGroupService->deleteGroup( $id );
    }
}
