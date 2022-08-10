<?php

namespace Encoda\Rbac\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Rbac\Repositories\Concrete\Database\PermissionRepository;
use Encoda\Rbac\Repositories\Concrete\Database\RoleRepository;
use Encoda\Rbac\Repositories\Interfaces\PermissionRepositoryInterface;
use Encoda\Rbac\Repositories\Interfaces\RoleRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( RoleRepositoryInterface::class, RoleRepository::class );
        $this->app->bind( PermissionRepositoryInterface::class, PermissionRepository::class );
    }
}
