<?php

namespace Encoda\Resource\Listeners;

use Encoda\Identity\Http\Requests\Admin\AdminCreateUserRequest;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;
use Encoda\Resource\Models\ResourceOwner;

class CreateResourceOwner
{

    public function __construct(
        protected AdminUserServiceInterface $adminUserService,
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @param ResourceOwner $owner
     */
    public function handle( $owner ) {

        if( $owner->is_invite ) {

            $user = $this->userRepository
                ->findByField( 'email', $owner->email )
                ->first();

            // User already exists, then do nothing
            if( $user ) {
                return;
            }
            $request = new AdminCreateUserRequest();

            $request->merge(
                [
                    "username" => $owner->email,
                    "firstName" => $owner->first_name,
                    "lastName" => $owner->last_name,
                ]
            );

            $this->adminUserService->adminCreateUser( $request );
        }
    }
}
