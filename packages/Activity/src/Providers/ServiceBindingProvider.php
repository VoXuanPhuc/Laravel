<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Services\Concrete\ActivityService;
use Encoda\Activity\Services\Concrete\ApplicationService;
use Encoda\Activity\Services\Concrete\EquipmentService;
use Encoda\Activity\Services\Concrete\RemoteAccessFactorService;
use Encoda\Activity\Services\Concrete\UtilityService;
use Encoda\Activity\Services\Interfaces\ActivityEquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityRemoteAccessServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Services\Interfaces\ApplicationServiceInterface;
use Encoda\Activity\Services\Concrete\ActivityEquipmentService;
use Encoda\Activity\Services\Concrete\ActivityRemoteAccessService;
use Encoda\Activity\Services\Interfaces\EquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\RemoteAccessFactorServiceInterface;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ActivityServiceInterface::class, ActivityService::class );
        $this->app->bind( ActivityRemoteAccessServiceInterface::class, ActivityRemoteAccessService::class );
        $this->app->bind( ActivityEquipmentServiceInterface::class, ActivityEquipmentService::class );
        $this->app->bind( UtilityServiceInterface::class, UtilityService::class );
        $this->app->bind( ApplicationServiceInterface::class, ApplicationService::class );
        $this->app->bind( EquipmentServiceInterface::class, EquipmentService::class );
        $this->app->bind( RemoteAccessFactorServiceInterface::class, RemoteAccessFactorService::class );
    }
}
