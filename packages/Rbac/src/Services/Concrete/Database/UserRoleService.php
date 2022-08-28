<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;
use Encoda\Rbac\Services\Interfaces\UserRoleServiceInterface;
use Illuminate\Http\Request;

class UserRoleService implements UserRoleServiceInterface
{

    public function __construct(
        protected UserServiceInterface $userService,
        protected RoleServiceInterface $roleService
    )
    {
    }

    /**
     * @param $userUid
     * @param Request $request
     * @throws NotFoundException
     */
    public function assignUserRole($userUid, Request $request )
    {
        /** @var CognitoUser $user */
        $user = $this->userService->getUser( $userUid );

        if( !$user ) {
            throw new NotFoundException(__('rbac::app.user.not_found'));
        }

        $role = $this->roleService->getRole( $request->get('role_uid') );

        $user->assignRole( $role );
    }
}
