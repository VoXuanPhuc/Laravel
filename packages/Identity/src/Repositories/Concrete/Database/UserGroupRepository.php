<?php

namespace Encoda\Identity\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Identity\Contracts\UserGroupContract;
use Encoda\Identity\Repositories\Interfaces\UserGroupRepositoryInterface;

class UserGroupRepository extends Repository implements UserGroupRepositoryInterface
{

    public function model()
    {
        return UserGroupContract::class;
    }
}
