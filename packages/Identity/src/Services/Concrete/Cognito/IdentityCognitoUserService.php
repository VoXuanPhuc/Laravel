<?php
namespace Encoda\Identity\Services\Concrete\Cognito;

use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Identity\Http\Requests\User\UpdateUserRequest;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;

class IdentityCognitoUserService implements UserServiceInterface
{

    protected UserRepositoryInterface $userRepository;
    protected CognitoUserService $cognitoUserService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        CognitoUserService $cognitoUserService
    )
    {
        $this->userRepository = $userRepository;
        $this->cognitoUserService = $cognitoUserService;
    }


    /**
     * @param $id
     * @return int
     */
    public function deleteUser($id)
    {
        return $this->userRepository->delete( $id );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUser($id)
    {
        return $this->userRepository->find( $id );
    }

    public function updateUser( UpdateUserRequest $request, $id )
    {

        $this->userRepository->update( $request->all(), $id );
    }

    /**
     * @return UserContract[]
     */
    public function listUsers()
    {
        return $this->userRepository->all();
    }


    /**
     * @param CreateUserRequest $request
     * @return UserContract
     */
    public function createUser( CreateUserRequest $request)
    {
        return $this->userRepository->create( $request->all() );

    }


    public function confirmSignup(\Illuminate\Http\Request $request)
    {
        return $this->cognitoUserService->confirmSignup(
            $request->username,
            $request->confirmationCode,
            $request->ip()
        );
    }

    public function authenticate($username, $password)
    {
        return $this->cognitoUserService->authenticate( $username, $password );
    }
}
