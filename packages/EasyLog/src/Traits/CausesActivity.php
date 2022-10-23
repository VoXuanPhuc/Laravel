<?php

namespace Encoda\EasyLog\Traits;

use Encoda\EasyLog\Exceptions\InvalidConfiguration;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Encoda\EasyLog\Providers\EasyLogServiceProvider;

trait CausesActivity
{
    /**
     * @throws InvalidConfiguration
     */
    public function actions(): MorphMany
    {
        return $this->morphMany(
            EasyLogServiceProvider::determineEasyLogModel(),
            'causer'
        );
    }
}
