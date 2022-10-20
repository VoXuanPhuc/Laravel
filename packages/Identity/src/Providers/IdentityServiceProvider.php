<?php
namespace Encoda\Identity\Providers;

class IdentityServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api-admin.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
    }

    public function register()
    {
        // Other providers
        $this->app->register( EventServiceProvider::class );

        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class );

        //Service binding
        $this->app->register(ServiceBindingProvider::class );

        //Module service
        $this->app->register(ModuleServiceProvider::class );

    }
}
