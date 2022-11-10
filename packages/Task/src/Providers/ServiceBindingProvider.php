<?php

namespace Encoda\Task\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Dashboard\Services\Concrete\SystemStatisticService;
use Encoda\Dashboard\Services\Interfaces\SystemStatisticServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( SystemStatisticServiceInterface::class, SystemStatisticService::class );

    }
}