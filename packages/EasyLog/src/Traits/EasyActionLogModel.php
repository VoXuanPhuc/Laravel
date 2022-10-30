<?php

namespace Encoda\EasyLog\Traits;

use Encoda\EasyLog\Models\EasyLog;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model
 * @method HasMany hasMany(string $class)
 */
trait EasyActionLogModel
{


    public function logs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {

        return $this->morphMany( EasyLog::class, 'subject');
    }
}
