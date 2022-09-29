<?php

namespace Encoda\Resource\Providers;

use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
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
    }
}



