<?php

namespace Encoda\Rbac\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Rbac\Services\Concrete\Database\PermissionService;
use Encoda\Rbac\Services\Concrete\Database\RoleService;
use Encoda\Rbac\Services\Interfaces\PermissionServiceInterface;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( RoleServiceInterface::class, RoleService::class );
        $this->app->bind( PermissionServiceInterface::class, PermissionService::class );
    }
}
