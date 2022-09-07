<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{

    protected $table = 'industries';

    protected $hidden = [
        'id',
        'pivot',
    ];
}
