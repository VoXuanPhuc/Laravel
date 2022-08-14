<?php

namespace Encoda\Identity\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Identity\Services\Concrete\AdminClientService;
use Encoda\Identity\Services\Concrete\AdminUserService;
use Encoda\Identity\Services\Concrete\CognitoAdminUserService;
use Encoda\Identity\Services\Concrete\UserGroupService;
use Encoda\Identity\Services\Concrete\UserService;
use Encoda\Identity\Services\Interfaces\AdminClientServiceInterface;
use Encoda\Identity\Services\Interfaces\AdminUserServiceInterface;
use Encoda\Identity\Services\Interfaces\UserGroupServiceInterface;
use Encoda\Identity\Services\Interfaces\UserServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( AdminUserServiceInterface::class, CognitoAdminUserService::class );
        $this->app->bind( AdminClientServiceInterface::class, AdminClientService::class );
        $this->app->bind( UserServiceInterface::class, UserService::class );
        $this->app->bind( UserGroupServiceInterface::class, UserGroupService::class );

    }
}
