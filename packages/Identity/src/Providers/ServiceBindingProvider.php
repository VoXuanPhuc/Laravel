<?php

namespace Encoda\Identity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Identity\Services\Concrete\Cognito\IdentityCognitoAdminClientService;
use Encoda\Identity\Services\Concrete\Cognito\IdentityCognitoAdminService;
use Encoda\Identity\Services\Concrete\Cognito\IdentityCognitoUserGroupService;
use Encoda\Identity\Services\Concrete\Cognito\IdentityCognitoUserService;
use Encoda\Identity\Services\Interfaces\AdminClientServiceInterface;
use Encoda\Identity\Services\Interfaces\AdminServiceInterface;
use Encoda\Identity\Services\Interfaces\UserGroupServiceInterface;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( AdminServiceInterface::class, IdentityCognitoAdminService::class );
        $this->app->bind( AdminClientServiceInterface::class, IdentityCognitoAdminClientService::class );
        $this->app->bind( UserServiceInterface::class, IdentityCognitoUserService::class );
        $this->app->bind( UserGroupServiceInterface::class, IdentityCognitoUserGroupService::class );

    }
}
