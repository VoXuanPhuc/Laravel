<?php

namespace Encoda\Dependency\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Dependency\Repositories\Concrete\DependencyDetailRepository;
use Encoda\Dependency\Repositories\Concrete\DependencyRepository;
use Encoda\Dependency\Repositories\Concrete\DependencyScenarioRepository;
use Encoda\Dependency\Repositories\Interfaces\DependencyDetailRepositoryInterface;
use Encoda\Dependency\Repositories\Interfaces\DependencyRepositoryInterface;
use Encoda\Dependency\Repositories\Interfaces\DependencyScenarioRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind(DependencyRepositoryInterface::class, DependencyRepository::class);
        $this->app->bind(DependencyScenarioRepositoryInterface::class, DependencyScenarioRepository::class);
        $this->app->bind(DependencyDetailRepositoryInterface::class, DependencyDetailRepository::class);
    }
}
