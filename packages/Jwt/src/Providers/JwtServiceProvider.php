<?php

namespace Encoda\Jwt\Providers;

use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register( \Tymon\JWTAuth\Providers\LumenServiceProvider::class );
    }
}
