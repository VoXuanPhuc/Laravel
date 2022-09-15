<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Services\Concrete\ActivityService;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ActivityServiceInterface::class, ActivityService::class );
    }
}
