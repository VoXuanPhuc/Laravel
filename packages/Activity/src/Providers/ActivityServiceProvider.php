<?php

namespace Encoda\Activity\Providers;

use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/admin-api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'activity');
    }

    public function register()
    {
        //event binding
        $this->app->register(ActivityEventServiceProvider::class);
        
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class);

        //Service binding
        $this->app->register(ServiceBindingProvider::class);
    }
}



