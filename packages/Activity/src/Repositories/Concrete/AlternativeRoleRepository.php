<?php

namespace Encoda\Activity\Repositories\Concrete;

use Encoda\Activity\Models\Role;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Core\Eloquent\Repository;

class AlternativeRoleRepository extends Repository implements AlternativeRoleRepositoryInterface
{
    
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Role::class;
    }
}
