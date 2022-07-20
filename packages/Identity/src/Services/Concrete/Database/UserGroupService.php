<?php

namespace Encoda\Identity\Services\Concrete\Database;

use Encoda\Identity\Http\Requests\UserGroup\CreateUserGroupRequest;
use Encoda\Identity\Http\Requests\UserGroup\UpdateUserGroupRequest;
use Encoda\Identity\Contracts\UserGroupContract;
use Encoda\Identity\Repositories\Interfaces\UserGroupRepositoryInterface;
use Encoda\Identity\Services\Interfaces\UserGroupServiceInterface;

class UserGroupService implements UserGroupServiceInterface
{


    protected UserGroupRepositoryInterface $userGroupRepository;

    public function __construct( UserGroupRepositoryInterface $userGroupRepository )
    {
        $this->userGroupRepository = $userGroupRepository;
    }


    /**
     * @return UserGroupContract[]
     */
    public function listGroup() {
        return $this->userGroupRepository->all();
    }


    public function createGroup( CreateUserGroupRequest $request ) {

        return $this->userGroupRepository->create( $request->all() );
    }

    /**
     * @param UpdateUserGroupRequest $request
     * @param $id
     * @return mixed
     */
    public function updateGroup( UpdateUserGroupRequest $request, $id ) {

        return $this->userGroupRepository->update( $request->all(), $id );
    }


    /**
     * @param $id
     * @return UserGroupContract
     */
    public function getGroup($id)
    {
        return $this->userGroupRepository->find( $id );
    }


    public function deleteGroup( $id ) {
        return $this->userGroupRepository->delete( $id );
    }


}
