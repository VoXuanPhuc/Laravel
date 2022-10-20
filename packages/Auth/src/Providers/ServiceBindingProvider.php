<?php

namespace Encoda\Auth\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Auth\Interfaces\AuthChallengeServiceInterface;
use Encoda\Auth\Interfaces\AuthServiceInterface;
use Encoda\Auth\Services\AuthChallengeService;
use Encoda\Auth\Services\AuthService;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( AuthServiceInterface::class, AuthService::class );
        $this->app->bind( AuthChallengeServiceInterface::class, AuthChallengeService::class );

    }
}
