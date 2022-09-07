<?php

namespace Encoda\EDocs\Providers;

use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{

    public function boot() {

        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ .'/../Resources/lang', 'edocs' );
    }

    public function register()
    {

        $this->app->register( DocumentServiceBindingProvider::class );
    }
}
