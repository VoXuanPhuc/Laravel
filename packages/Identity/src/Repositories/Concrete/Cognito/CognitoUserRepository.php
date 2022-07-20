<?php

namespace Encoda\Identity\Repositories\Concrete\Cognito;

use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Models\Cognito\CognitoUser;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Event;


class CognitoUserRepository extends CognitoIdentityBaseRepository implements UserRepositoryInterface
{

    /**
     * @return string
     */
    public function model()
    {
        return CognitoUser::class;
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes)
    {
        Event::dispatch( 'identity.user.create.before' );

        $user = $this->model->create( $attributes );

        Event::dispatch( 'identity.user.create.after' );
        return $user;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return User
     */
    public function update(array $attributes, $id)
    {
        /** @var User $user */
        $user = $this->find( $id );
        $user->update($attributes, $id);

        return $user;
    }

    public function delete($id)
    {
        $user = $this->find( $id );
        $user->delete();
    }
}
