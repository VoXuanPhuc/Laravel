<?php

namespace Encoda\CORS\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\CORS\Contracts\CORSServiceInterface;
use Encoda\CORS\Services\CORSService;

class CORSServiceProvider extends ServiceProvider
{

    const CONFIG_KEY = 'cors';

    /**
     * @inheritdoc
     */
    public function register(): void
    {
        // In Lumen application configuration files needs to be loaded implicitly
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $this->app->configure(self::CONFIG_KEY);
        } else {
            $this->publishes(
                [
                    $this->configPath() => config_path('cors.php')
                ]
            );
        }

        $this->registerBindings();
    }


    /**
     * Registers container bindings.
     */
    protected function registerBindings(): void
    {
        $this->app->bind(CORSService::class, function () {
            return new CorsService(config(self::CONFIG_KEY));
        });

        $this->app->alias(CorsService::class, CORSServiceInterface::class);
    }

    /**
     * Default config file path
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/cors.php';
    }
}
