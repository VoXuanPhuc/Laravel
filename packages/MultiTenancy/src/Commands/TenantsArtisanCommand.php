<?php

namespace Encoda\MultiTenancy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Encoda\MultiTenancy\Commands\Concerns\TenantAware;
use Encoda\MultiTenancy\Concerns\UsesMultitenancyConfig;
use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;
use Encoda\MultiTenancy\Models\Tenant;

class TenantsArtisanCommand extends Command
{
    use UsesTenantModel;
    use UsesMultitenancyConfig;
    use TenantAware;

    protected $signature = 'tenants:artisan {artisanCommand} {--tenant=*}';

    public function handle()
    {
        if (! $artisanCommand = $this->argument('artisanCommand')) {
            $artisanCommand = $this->ask('Which artisan command do you want to run for all tenants?');
        }

        $artisanCommand = addslashes($artisanCommand);

        $tenant = Tenant::current();

        $this->line('');
        $this->info("Running command for tenant `{$tenant->name}` (id: {$tenant->getKey()})...");
        $this->line("---------------------------------------------------------");

        Artisan::call($artisanCommand, [], $this->output);
    }
}
