<?php

namespace Encoda\Identity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Identity\Repositories\Concrete\Cognito\CognitoAppClientRepository;
use Encoda\Identity\Repositories\Concrete\Cognito\CognitoUserGroupRepository;
use Encoda\Identity\Repositories\Concrete\Cognito\CognitoUserRepository;
use Encoda\Identity\Repositories\Concrete\Database\ClientRepository;
use Encoda\Identity\Repositories\Concrete\Database\UserGroupRepository;
use Encoda\Identity\Repositories\Concrete\Database\UserRepository;
use Encoda\Identity\Repositories\Interfaces\ClientRepositoryInterface;
use Encoda\Identity\Repositories\Interfaces\UserGroupRepositoryInterface;
use Encoda\Identity\Repositories\Interfaces\UserRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        if( config('config.identity_pool.driver') == 'cognito' ) {
            $this->app->bind( ClientRepositoryInterface::class, CognitoAppClientRepository::class );
            $this->app->bind( UserRepositoryInterface::class, CognitoUserRepository::class );
            $this->app->bind( UserGroupRepositoryInterface::class, CognitoUserGroupRepository::class );
        }
        else {
            $this->app->bind( ClientRepositoryInterface::class, ClientRepository::class );
            $this->app->bind( UserRepositoryInterface::class, UserRepository::class );
            $this->app->bind( UserGroupRepositoryInterface::class, UserGroupRepository::class );
        }
    }
}
