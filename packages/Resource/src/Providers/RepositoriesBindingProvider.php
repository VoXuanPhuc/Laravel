<?php

namespace Encoda\Resource\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Resource\Models\ResourceCategory;
use Encoda\Resource\Repositories\Concrete\ResourceCategoryRepository;
use Encoda\Resource\Repositories\Concrete\ResourceOwnerRepository;
use Encoda\Resource\Repositories\Concrete\ResourceRepository;
use Encoda\Resource\Repositories\Interfaces\ResourceCategoryRepositoryInterface;
use Encoda\Resource\Repositories\Interfaces\ResourceOwnerRepositoryInterface;
use Encoda\Resource\Repositories\Interfaces\ResourceRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( ResourceRepositoryInterface::class, ResourceRepository::class );
        $this->app->bind( ResourceOwnerRepositoryInterface::class, ResourceOwnerRepository::class );
        $this->app->bind( ResourceCategoryRepositoryInterface::class, ResourceCategoryRepository::class );

    }
}
