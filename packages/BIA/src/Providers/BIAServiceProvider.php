<?php

namespace Encoda\BIA\Providers;

use Encoda\BIA\Models\BIA;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class BIAServiceProvider extends ServiceProvider
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
     * Morph Map
     */
    protected function morphMap() {
        Relation::morphMap([
            'BIA' => BIA::class,
        ]);
    }
}
