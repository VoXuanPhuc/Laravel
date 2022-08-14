<?php

namespace Encoda\Identity\Models\Database;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encoda\Identity\Contracts\UserContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract, UserContract
{
    use Authenticatable, Authorizable, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'password',
        'api_token',
        'token',
        'status',
        'is_verified',
        'is_suspended',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    public function create($attributes)
    {
       //TODO:
    }

    public function find($id)
    {
        //TODO:
    }

    public function list($columns = ['*'])
    {
        //TODO:
    }
}
