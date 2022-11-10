<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Services\Concrete\ActivityDependencyService;
use Encoda\Activity\Services\Concrete\ActivityService;
use Encoda\Activity\Services\Concrete\ActivityTolerablePeriodDisruptionService;
use Encoda\Activity\Services\Concrete\ApplicationService;
use Encoda\Activity\Services\Concrete\DisruptionScenarioService;
use Encoda\Activity\Services\Concrete\EquipmentService;
use Encoda\Activity\Services\Concrete\RecoveryTimeDisruptionScenarioService;
use Encoda\Activity\Services\Concrete\RecoveryTimeService;
use Encoda\Activity\Services\Concrete\RemoteAccessFactorService;
use Encoda\Activity\Services\Concrete\TolerableTimePeriodService;
use Encoda\Activity\Services\Concrete\UtilityService;
use Encoda\Activity\Services\Interfaces\ActivityDependencyServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityEquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityRemoteAccessServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityServiceInterface;
use Encoda\Activity\Services\Interfaces\ActivityTolerablePeriodDisruptionServiceInterface;
use Encoda\Activity\Services\Interfaces\ApplicationServiceInterface;
use Encoda\Activity\Services\Concrete\ActivityEquipmentService;
use Encoda\Activity\Services\Concrete\ActivityRemoteAccessService;
use Encoda\Activity\Services\Interfaces\DisruptionScenarioServiceInterface;
use Encoda\Activity\Services\Interfaces\EquipmentServiceInterface;
use Encoda\Activity\Services\Interfaces\RecoveryTimeDisruptionScenarioServiceInterface;
use Encoda\Activity\Services\Interfaces\RecoveryTimeServiceInterface;
use Encoda\Activity\Services\Interfaces\RemoteAccessFactorServiceInterface;
use Encoda\Activity\Services\Interfaces\TolerableTimePeriodServiceInterface;
use Encoda\Activity\Services\Interfaces\UtilityServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ActivityServiceInterface::class, ActivityService::class );
        $this->app->bind( ActivityRemoteAccessServiceInterface::class, ActivityRemoteAccessService::class );
        $this->app->bind( ActivityEquipmentServiceInterface::class, ActivityEquipmentService::class );
        $this->app->bind( ActivityDependencyServiceInterface::class, ActivityDependencyService::class );
        $this->app->bind( UtilityServiceInterface::class, UtilityService::class );
        $this->app->bind( ApplicationServiceInterface::class, ApplicationService::class );
        $this->app->bind( EquipmentServiceInterface::class, EquipmentService::class );
        $this->app->bind( RemoteAccessFactorServiceInterface::class, RemoteAccessFactorService::class );
        $this->app->bind( TolerableTimePeriodServiceInterface::class, TolerableTimePeriodService::class );
        $this->app->bind( ActivityTolerablePeriodDisruptionServiceInterface::class, ActivityTolerablePeriodDisruptionService::class );
        $this->app->bind( RecoveryTimeServiceInterface::class, RecoveryTimeService::class );
        $this->app->bind( DisruptionScenarioServiceInterface::class, DisruptionScenarioService::class );
        $this->app->bind( RecoveryTimeDisruptionScenarioServiceInterface::class, RecoveryTimeDisruptionScenarioService::class );
    }
}
