<?php

namespace Encoda\BCP\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\BCP\Services\Concrete\BCPExportingService;
use Encoda\BCP\Services\Concrete\BCPService;
use Encoda\BCP\Services\Interfaces\BCPExportingServiceInterface;
use Encoda\BCP\Services\Interfaces\BCPServiceInterface;
use Encoda\BCP\Services\Concrete\BCPLogService;
use Encoda\BCP\Services\Interfaces\BCPLogServiceInterface;

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
        $this->app->bind(BCPServiceInterface::class, BCPService::class);
        $this->app->bind(BCPExportingServiceInterface::class, BCPExportingService::class);
        $this->app->bind(BCPLogServiceInterface::class, BCPLogService::class);
    }
}
