<?php

namespace Encoda\Auth\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Auth\Services\AuthService;
use Encoda\Auth\Services\CognitoAuthService;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {

        $this->app->bind( AuthServiceInterface::class, CognitoAuthService::class );
    }
}
