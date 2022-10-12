<?php

namespace Encoda\Organization\Models;


use Encoda\Core\Models\Model;

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
