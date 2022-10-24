<?php

namespace Encoda\Supplier\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\Relation;

class SupplierServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ .'/../Resources/lang', 'org' );
    }

    public function register()
    {
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class );

        //Service binding
        $this->app->register(ServicesBindingProvider::class );

        // Morph map
        $this->morphMap();
    }

    /**
     * Morph map
     */
    protected function morphMap() {
        Relation::morphMap([
            'Supplier' => Supplier::class,
        ]);
    }
}
