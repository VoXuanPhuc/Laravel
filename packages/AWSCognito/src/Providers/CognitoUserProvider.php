<?php

namespace Encoda\AWSCognito\Providers;

use Encoda\AWSCognito\Services\CognitoAdminService;
use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Identity\Repositories\Concrete\Database\UserRepository;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class CognitoUserProvider implements UserProvider
{
    
    protected UserRepositoryInterface $userRepository;
    protected CognitoAdminService $adminService;
    protected CognitoUserService $userService;


    public function __construct()
    {
        $this->userRepository = app(UserRepository::class );
        $this->adminService = app( CognitoAdminService::class );
        $this->userService = app( CognitoUserService::class );

    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|mixed|null
     * @throws BadRequestException
     */
    public function retrieveById($identifier)
    {
        // Find in the local first
        $user = $this->userRepository->findByUid( $identifier );

        // Fallback to email
        if( !$user ) {
            $user = $this->userRepository->findByEmail( $identifier );
        }

        // If still not found, then get from Cognito
        if( !$user ) {
            $user = $this->adminService->adminGetUser( $identifier )?->getLinkedUser();
        }

        return $user;
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|void|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @throws BadRequestException
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->retrieveById( $credentials['email'] );
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return strlen( $user->email ) == strlen( $credentials['email'] )
                && strlen( $credentials['password']) > 0 ;
    }
}
