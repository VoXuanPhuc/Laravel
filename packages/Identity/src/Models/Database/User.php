<?php

namespace Encoda\Identity\Models\Database;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Encoda\Identity\Contracts\UserContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, UserContract, JWTSubject
{
    use HasRoles;
    use Authenticatable, Authorizable, HasFactory;

    protected $fillable = [
        'uid',
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
        'id',
        'password',
        'api_token',
        'remember_token',
    ];

    protected $appends = [
        'name'
    ];

    /**
     * @return string
     */
    public function getNameAttribute() {
        return implode( ' ', [ $this->first_name, $this->last_name ] );
    }

        /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function guardName() {
        return 'api';
    }
}
