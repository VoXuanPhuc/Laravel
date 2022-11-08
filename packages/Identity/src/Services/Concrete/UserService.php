<?php
namespace Encoda\Identity\Services\Concrete;

use Encoda\Auth\Exceptions\UserNotFoundException;
use Encoda\AWSCognito\DTO\AuthChallengeDTO;
use Encoda\AWSCognito\DTO\TokenDTO;
use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Identity\Http\Requests\User\ReinviteUserRequest;
use Encoda\Identity\Http\Requests\User\UpdateUserRequest;
use Encoda\Identity\Http\Requests\User\CreateUserRequest;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        protected CognitoUserService $cognitoUserService,
    )
    {
        $this->userRepository = $userRepository;
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

        return $this->userRepository->update( $request->all(), $id );
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
        //TODO: Database confirm signup code
    }

    /**
     * @param $username
     * @param $password
     * @return AuthChallengeDTO|TokenDTO
     * @throws UserNotFoundException
     */
    public function authenticate($username, $password)
    {
       return $this->cognitoUserService->authenticate( $username, $password );
    }

    /**
     * @param ReinviteUserRequest $request
     * @param string              $id
     *
     * @return mixed
     */
    public function reinviteUser(string $id)
    {
        return $this->userRepository->reinviteUser($id);
    }
}
