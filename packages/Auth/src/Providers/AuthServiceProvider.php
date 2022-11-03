<?php
namespace Encoda\Auth\Providers;

class AuthServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ . '/../Resources/lang', 'auth' );
    }

    public function register()
    {
        //Register more providers
        $this->app->register( ServiceBindingProvider::class );
        $this->app->register( PasswordResetServiceProvider::class );


    }
}
