<?php

namespace Encoda\Dependency\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Dependency\Services\Concrete\DependableService;
use Encoda\Dependency\Services\Concrete\DependencyFactorService;
use Encoda\Dependency\Services\Concrete\DependencyScenarioService;
use Encoda\Dependency\Services\Concrete\DependencyService;
use Encoda\Dependency\Services\Interfaces\DependableServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyFactorServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyServiceInterface;


class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind(DependencyServiceInterface::class, DependencyService::class);
        $this->app->bind(DependencyScenarioServiceInterface::class, DependencyScenarioService::class);
        $this->app->bind(DependencyFactorServiceInterface::class, DependencyFactorService::class);
        $this->app->bind(DependableServiceInterface::class, DependableService::class);
    }
}
