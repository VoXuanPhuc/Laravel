<?php

namespace Encoda\AWSCognito\Providers;

use Encoda\AWSCognito\Brokers\CognitoPasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;

class CognitoPasswordResetServiceProvider extends PasswordResetServiceProvider
{

    protected function registerPasswordBroker()
    {


        $this->app->singleton('auth.password', function ($app) {
            return new  PasswordBrokerManager( $app );
        });

        if( config('config.identity_pool.driver') == 'cognito' ) {
            $this->app->bind('auth.password.broker', CognitoPasswordBroker::class );
        }
    }
}
