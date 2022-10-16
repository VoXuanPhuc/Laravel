<?php

namespace Encoda\Dependency\Providers;

use Encoda\Activity\Models\Activity;
use Encoda\Dependency\Enums\DependableObjectTypeEnum;
use Encoda\Resource\Models\Resource;
use Encoda\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class DependencyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api-admin.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'resource');
    }

    public function register()
    {
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class );

        //Service binding
        $this->app->register(ServiceBindingProvider::class );

        Relation::morphMap([
            DependableObjectTypeEnum::ACTIVITY->value => Activity::class,
            DependableObjectTypeEnum::RESOURCE->value => Resource::class,
            DependableObjectTypeEnum::SUPPLIER->value => Supplier::class,
        ]);
    }
}
