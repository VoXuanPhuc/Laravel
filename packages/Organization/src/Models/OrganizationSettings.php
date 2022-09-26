<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationSettings extends Model
{

    protected $table = 'organization_settings';

    protected $hidden = [
        'organization_id',
    ];

}
