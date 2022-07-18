<?php

namespace Encoda\Auth\Repositories;

use Encoda\Auth\Models\Role;
use Encoda\Core\Eloquent\Repository;

class RoleRepository extends Repository
{

    public function model()
    {
        return Role::class;
    }
}
