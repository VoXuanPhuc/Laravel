<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Repositories\Concrete\Database\ActivityRepository;
use Encoda\Activity\Repositories\Concrete\Database\ApplicationRepository;
use Encoda\Activity\Repositories\Concrete\Database\DeviceRepository;
use Encoda\Activity\Repositories\Concrete\Database\RemoteAccessFactorRepository;
use Encoda\Activity\Repositories\Concrete\Database\UtilityRepository;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\DeviceRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\RemoteAccessFactorRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\UtilityRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ActivityRepositoryInterface::class, ActivityRepository::class );
        $this->app->bind( UtilityRepositoryInterface::class, UtilityRepository::class );
        $this->app->bind( RemoteAccessFactorRepositoryInterface::class, RemoteAccessFactorRepository::class );
        $this->app->bind( ApplicationRepositoryInterface::class, ApplicationRepository::class );
        $this->app->bind( DeviceRepositoryInterface::class, DeviceRepository::class );
    }
}
