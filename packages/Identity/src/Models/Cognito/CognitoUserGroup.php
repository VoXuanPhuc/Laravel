<?php

namespace Encoda\Identity\Models\Cognito;

use Encoda\AWSCognito\Services\CognitoUserGroupService;
use Encoda\Identity\Contracts\UserGroupContract;

class CognitoUserGroup extends CognitoBaseModel implements UserGroupContract
{
    public $id;

    private CognitoUserGroupService $groupService;

    public function __construct(CognitoUserGroupService $groupService, array $attributes = [] )
    {
        $this->groupService = $groupService;
        parent::__construct( $attributes);
    }

    public function create( $attributes )
    {
        return $this->groupService->createGroup(
            $attributes['name'],
            $attributes['desc'] ?? '',
        );
    }

    public function update(array $attributes = [], array $options = [])
    {
        return $this->groupService->updateGroup(
            $this->id,
            $attributes['desc'],
        );
    }

    public function find($id)
    {
        return $this->groupService->find( $id );
    }

    public function list($columns = ['*'])
    {
        return $this->groupService->listGroups();
    }

    public function delete()
    {
        return $this->groupService->deleteGroup( $this->id );
    }
}
