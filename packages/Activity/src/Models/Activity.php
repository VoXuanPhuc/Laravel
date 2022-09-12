<?php

namespace Encoda\Activity\Models;

use Encoda\Activity\Contract\ActivityContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model implements ActivityContract
{

    protected $fillable = [
        'name',
        'description',
        'number_of_location',
    ];

}
