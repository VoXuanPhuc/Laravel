<?php

namespace Encoda\Identity\Repositories\Concrete\Cognito;

use Encoda\AWSCognito\Models\CognitoUser;
use Encoda\AWSCognito\Services\CognitoAdminService;
use Encoda\AWSCognito\Services\CognitoUserService;
use Encoda\Identity\Models\Database\User;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Event;
use Prettus\Repository\Exceptions\RepositoryException;


class CognitoUserRepository extends CognitoIdentityBaseRepository implements UserRepositoryInterface
{

    protected CognitoUserService $cognitoUserService;
    protected CognitoAdminService $cognitoUserAdminService;

    public function __construct(
        Application $app,
        CognitoUserService $cognitoUserService,
        CognitoAdminService $cognitoUserAdminService
    )
    {
        $this->cognitoUserService = $cognitoUserService;
        $this->cognitoUserAdminService = $cognitoUserAdminService;
        parent::__construct($app);
    }

    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @param array $attributes
     * @return CognitoUser
     */
    public function create(array $attributes)
    {
        Event::dispatch( 'identity.cognito.user.create.before' );

       $user = $this->cognitoUserService->register( $attributes );

        Event::dispatch( 'identity.cognito.user.create.after', $user );
        return $user;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->cognitoUserAdminService->adminGetUser( $id );
    }

    public function lists($column, $key = null)
    {
        return [];
    }

    public function all($columns = ['*'])
    {
        return $this->cognitoUserService->listUsers();
    }

    /**
     * @param array $attributes
     * @param $id
     * @return User
     */
    public function update(array $attributes, $id)
    {
        return $this->cognitoUserAdminService->adminUpdateUser( $id, $attributes );
    }

    public function delete($id)
    {
        return $this->cognitoUserAdminService->adminDeleteUser( $id );
    }

    /**
     * @param $uid
     * @param string[] $column
     * @return mixed
     * @throws RepositoryException
     */
    public function findByUid($uid, $column = ['*'])
    {
        return $this->find( $uid, $column )->getLinkedUser();
    }

    public function findByEmail($email, array $column = ['*']): mixed
    {
        return $this->find( $email, $column )->getLinkedUser();
    }
}
