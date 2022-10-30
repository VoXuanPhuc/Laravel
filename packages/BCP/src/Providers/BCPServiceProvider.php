<?php

namespace Encoda\BCP\Providers;

use Encoda\BCP\Models\BCP;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class BCPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'resource');
    }

    /**
     * @return void
     */
    public function register()
    {
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class);

        //Service binding
        $this->app->register(ServiceBindingProvider::class);

        $this->morphMap();
    }

    /**
     * Map
     */
    protected function morphMap() {
        Relation::morphMap([
            'BCP' => BCP::class,
        ]);

    }
}
