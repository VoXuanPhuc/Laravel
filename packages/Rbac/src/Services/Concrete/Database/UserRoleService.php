<?php

namespace Encoda\Rbac\Services\Concrete\Database;

use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Models\Database\User;
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
     * @return CognitoUser|User
     * @throws NotFoundException
     */
    public function assignUserRole(Request $request, $userUid  )
    {
        /** @var User $user */
        $user = $this->userService->getUser( $userUid );

        if( $user instanceof CognitoUser && method_exists( $user, 'getLinkedUser') ) {
            $user = $user->getLinkedUser();
        }

        if( !$user ) {
            throw new NotFoundException(__('rbac::app.user.not_found'));
        }

        $role = $this->roleService->getRole( $request->get('role_uid') );

        $user->syncRoles( [$role ]);

        return $user;
    }
}
