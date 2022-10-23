<?php
namespace Encoda\Organization\Providers;

use Encoda\Organization\Models\BusinessUnit;
use Encoda\Organization\Models\Division;
use Encoda\Organization\Models\Industry;
use Encoda\Organization\Models\Organization;
use Encoda\Organization\Models\OrganizationOwner;
use Illuminate\Database\Eloquent\Relations\Relation;

class OrganizationServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/global-api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/web.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/admin-api.php');
        $this->loadRoutesFrom( __DIR__ . '/../Http/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');
        $this->loadTranslationsFrom( __DIR__ .'/../Resources/lang', 'org' );
    }

    public function register()
    {
        // Other providers
        $this->app->register( OrganizationEventServiceProvider::class );
        //Repositories binding
        $this->app->register(RepositoriesBindingProvider::class );

        //Service binding
        $this->app->register(ServiceBindingProvider::class );

        //Morph map
        $this->morphMap();

    }


    /**
     * Morph map
     */
    protected function morphMap() {
        Relation::morphMap([
            'Organization' => Organization::class,
            'Industry' => Industry::class,
            'OrganizationOwner' => OrganizationOwner::class,
            'Division' => Division::class,
            'BusinessUnit' => BusinessUnit::class,
        ]);
    }
}
