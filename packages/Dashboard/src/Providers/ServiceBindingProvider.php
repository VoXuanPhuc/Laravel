<?php

namespace Encoda\Dashboard\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Dashboard\Services\Concrete\SystemStatisticService;
use Encoda\Dashboard\Services\Concrete\TenantStatisticService;
use Encoda\Dashboard\Services\Interfaces\SystemStatisticServiceInterface;
use Encoda\Dashboard\Services\Interfaces\TenantStatisticServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( SystemStatisticServiceInterface::class, SystemStatisticService::class );
        $this->app->bind( TenantStatisticServiceInterface::class, TenantStatisticService::class );

    }
}
