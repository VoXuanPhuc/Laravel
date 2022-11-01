<?php

namespace Encoda\Notification\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{

    public function boot() {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/admin-api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ .'/../Resources/lang', 'dashboard' );
        $this->mergeConfigFrom( __DIR__ . '/../Config/notification.php', 'notification');
    }

    public function register()
    {
        $this->app->register( ServiceBindingProvider::class );
        $this->app->register( RepositoryBindingProvider::class );
    }
}
