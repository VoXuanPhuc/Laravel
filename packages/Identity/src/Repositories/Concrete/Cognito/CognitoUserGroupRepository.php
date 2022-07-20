<?php

namespace Encoda\Identity\Repositories\Concrete\Cognito;

use Encoda\Identity\Models\Cognito\CognitoUserGroup;
use Encoda\Identity\Repositories\Interfaces\UserGroupRepositoryInterface;
use Illuminate\Support\Facades\Event;

class CognitoUserGroupRepository extends CognitoIdentityBaseRepository implements UserGroupRepositoryInterface
{

    public function model()
    {
        return CognitoUserGroup::class;
    }

    public function delete($id)
    {
        //$model = $this->find( $id );

        $this->model->id = $id;
        return $this->model->delete();
    }

    public function create(array $attributes)
    {
        Event::dispatch( 'identity.user-group.create.before' );

        $userGroup = $this->model->create( $attributes );

        Event::dispatch( 'identity.user-group.create.after' );
        return $userGroup;
    }

    public function update(array $attributes, $id)
    {
        /** @var CognitoUserGroup $userGroup */
        $userGroup = $this->find( $id );

        $this->model->id = $id;
        return $this->model->update( $attributes );
    }
}
