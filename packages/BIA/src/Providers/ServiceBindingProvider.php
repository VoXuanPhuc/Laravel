<?php

namespace Encoda\BIA\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\BIA\Services\Concrete\BIAExportingService;
use Encoda\BIA\Services\Concrete\BIAService;
use Encoda\BIA\Services\Interfaces\BIAExportingServiceInterface;
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
        $this->app->bind(BIAExportingServiceInterface::class, BIAExportingService::class);
    }
}
