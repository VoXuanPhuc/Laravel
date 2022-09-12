<?php

namespace Encoda\Activity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Activity\Repositories\Concrete\Database\ActivityRepository;
use Encoda\Activity\Repositories\Interfaces\ActivityRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ActivityRepositoryInterface::class, ActivityRepository::class );
    }
}
