<?php


namespace Encoda\Auth\Providers;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider as BasePasswordResetServiceProvider;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordResetServiceProvider extends BasePasswordResetServiceProvider
{

    protected function registerAliases() {
        $this->app->alias('auth.password', PasswordBrokerManager::class );
        $this->app->alias('auth.password.broker', PasswordBroker::class );

    }

    public function register()
    {
        parent::register();

        $this->registerAliases();
        $this->registerTokenRespository();
    }


    protected function registerTokenRespository(){

        $this->app->bind( TokenRepositoryInterface::class, DatabaseTokenRepository::class );

    }

}
