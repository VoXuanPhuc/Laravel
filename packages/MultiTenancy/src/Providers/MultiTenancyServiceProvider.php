<?php

namespace Encoda\MultiTenancy\Providers;

use Encoda\MultiTenancy\MultiTenancy;
use Illuminate\Support\Facades\Event;
//use Laravel\Octane\Events\RequestReceived as OctaneRequestReceived;
//use Laravel\Octane\Events\RequestTerminated as OctaneRequestTerminated;
use Encoda\PackageTools\Package;
use Encoda\PackageTools\PackageServiceProvider;
use Encoda\MultiTenancy\Commands\TenantsArtisanCommand;
use Encoda\MultiTenancy\Concerns\UsesMultitenancyConfig;
use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;

class MultiTenancyServiceProvider extends PackageServiceProvider
{
    use UsesTenantModel;
    use UsesMultitenancyConfig;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('multitenancy')
            ->hasConfigFile('multitenancy')
            ->hasMigration('landlord/create_landlord_tenants_table')
            ->runsMigrations( true )
            ->hasCommand(TenantsArtisanCommand::class);
    }

    public function packageBooted(): void
    {
        $this->app->bind(MultiTenancy::class, fn ($app) => new MultiTenancy($app));

        if (! isset($_SERVER['LARAVEL_OCTANE'])) {
            app(MultiTenancy::class)->start();

            return;
        }

//        Event::listen(fn (OctaneRequestReceived $requestReceived) => app(MultiTenancy::class)->start());
//        Event::listen(fn (OctaneRequestTerminated $requestTerminated) => app(MultiTenancy::class)->end());
    }
}
