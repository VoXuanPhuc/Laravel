<?php

namespace Encoda\Identity\Models\Database;

use Encoda\Identity\Contracts\UserContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserContract
{

    public function create($attributes)
    {
        // TODO: Implement create() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function list($columns = ['*'])
    {
        // TODO: Implement list() method.
    }
}
