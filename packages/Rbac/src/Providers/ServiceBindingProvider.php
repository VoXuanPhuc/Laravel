<?php

namespace Encoda\Rbac\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Rbac\Services\Concrete\Database\PermissionGroupService;
use Encoda\Rbac\Services\Concrete\Database\PermissionService;
use Encoda\Rbac\Services\Concrete\Database\RolePermissionService;
use Encoda\Rbac\Services\Concrete\Database\RoleService;
use Encoda\Rbac\Services\Concrete\Database\UserRoleService;
use Encoda\Rbac\Services\Interfaces\PermissionGroupServiceInterface;
use Encoda\Rbac\Services\Interfaces\PermissionServiceInterface;
use Encoda\Rbac\Services\Interfaces\RolePermissionServiceInterface;
use Encoda\Rbac\Services\Interfaces\RoleServiceInterface;
use Encoda\Rbac\Services\Interfaces\UserRoleServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( RoleServiceInterface::class, RoleService::class );
        $this->app->bind( PermissionGroupServiceInterface::class, PermissionGroupService::class );
        $this->app->bind( PermissionServiceInterface::class, PermissionService::class );
        $this->app->bind( RolePermissionServiceInterface::class, RolePermissionService::class );
        $this->app->bind( UserRoleServiceInterface::class, UserRoleService::class );
    }
}
