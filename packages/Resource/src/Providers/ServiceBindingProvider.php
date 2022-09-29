<?php

namespace Encoda\Resource\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Resource\Services\Concrete\ResourceCategoryService;
use Encoda\Resource\Services\Concrete\ResourceOwnerService;
use Encoda\Resource\Services\Concrete\ResourceService;
use Encoda\Resource\Services\Interfaces\ResourceCategoryServiceInterface;
use Encoda\Resource\Services\Interfaces\ResourceOwnerServiceInterface;
use Encoda\Resource\Services\Interfaces\ResourceServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ResourceServiceInterface::class, ResourceService::class );
        $this->app->bind( ResourceOwnerServiceInterface::class, ResourceOwnerService::class );
        $this->app->bind( ResourceCategoryServiceInterface::class, ResourceCategoryService::class );

    }
}
