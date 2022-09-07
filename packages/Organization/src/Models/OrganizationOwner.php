<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationOwner extends Model
{

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
