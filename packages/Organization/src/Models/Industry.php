<?php

namespace Encoda\Organization\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    // code if name has been used
    const NAME_UNIQUE_CODE = "NAME_UNIQUE";

    protected $table = 'industries';

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $hidden = [
        'id',
        'pivot',
    ];
}
