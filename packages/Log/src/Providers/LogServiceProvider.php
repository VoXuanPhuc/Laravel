<?php

namespace Encoda\Log\Providers;

use Illuminate\Support\ServiceProvider;

/**
 *
 */
class LogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
