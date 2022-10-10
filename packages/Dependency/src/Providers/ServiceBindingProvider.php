<?php

namespace Encoda\Dependency\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Dependency\Services\Concrete\DependencyDetailService;
use Encoda\Dependency\Services\Concrete\DependencyScenarioService;
use Encoda\Dependency\Services\Concrete\DependencyService;
use Encoda\Dependency\Services\Interfaces\DependencyDetailServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyScenarioServiceInterface;
use Encoda\Dependency\Services\Interfaces\DependencyServiceInterface;


class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind(DependencyServiceInterface::class, DependencyService::class);
        $this->app->bind(DependencyScenarioServiceInterface::class, DependencyScenarioService::class);
        $this->app->bind(DependencyDetailServiceInterface::class, DependencyDetailService::class);
    }
}
