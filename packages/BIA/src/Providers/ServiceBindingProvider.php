<?php

namespace Encoda\BIA\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\BIA\Services\Concrete\BIAService;
use Encoda\BIA\Services\Interfaces\BIAServiceInterface;

/**
 *
 */
class ServiceBindingProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->app->bind(BIAServiceInterface::class, BIAService::class);
    }
}
