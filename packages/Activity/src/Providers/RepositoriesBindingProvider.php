<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Repositories\Concrete\ActivityRepository;
use Encoda\Activity\Repositories\Concrete\AlternativeRoleRepository;
use Encoda\Activity\Repositories\Concrete\ApplicationRepository;
use Encoda\Activity\Repositories\Concrete\EquipmentRepository;
use Encoda\Activity\Repositories\Concrete\RemoteAccessFactorRepository;
use Encoda\Activity\Repositories\Concrete\UtilityRepository;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\AlternativeRoleRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\ApplicationRepositoryInterface;
use Encoda\Activity\Repositories\Interfaces\EquipmentRepositoryInterface;
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
        $this->app->bind( EquipmentRepositoryInterface::class, EquipmentRepository::class );
        $this->app->bind( AlternativeRoleRepositoryInterface::class, AlternativeRoleRepository::class );
    }
}
