<?php

namespace Encoda\Organization\Models;

use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogTrait;
use Illuminate\Database\Eloquent\Model;

class OrganizationOwner extends Model
{

    use EasyActionLogTrait;

    protected $table = 'organization_owners';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    protected $hidden = [
        'id',
        'organization_id',
        'created_at',
        'updated_at',
    ];
}
