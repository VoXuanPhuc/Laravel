<?php

namespace Encoda\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{

    public function boot() {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/admin-api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ .'/../Resources/lang', 'dashboard' );
        $this->mergeConfigFrom( __DIR__ .'/../Config/dashboard.php', 'dashboard' );
    }

    public function register()
    {
        $this->app->register( ServiceBindingProvider::class );
    }
}
