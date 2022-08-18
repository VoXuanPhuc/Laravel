<?php

namespace Encoda\Identity\Repositories\Concrete\Database;

use Encoda\Core\Eloquent\Repository;
use Encoda\Identity\Contracts\UserContract;
use Encoda\Identity\Models\Cognito\CognitoUser;
use Encoda\Identity\Models\Database\User;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Event;


class UserRepository extends Repository implements UserRepositoryInterface
{


    public function model()
    {
        return User::class;
    }
}