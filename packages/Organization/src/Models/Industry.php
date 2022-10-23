<?php

namespace Encoda\Organization\Models;


use Encoda\Core\Models\Model;
use Encoda\EasyLog\Entities\LogOptions;
use Encoda\EasyLog\Traits\EasyActionLogTrait;

class Industry extends Model
{
    use EasyActionLogTrait;

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
