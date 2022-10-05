<?php

namespace Encoda\Supplier\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Supplier\Repositories\Concrete\SupplierCategoryRepository;
use Encoda\Supplier\Repositories\Concrete\SupplierCertRepository;
use Encoda\Supplier\Repositories\Concrete\SupplierRepository;
use Encoda\Supplier\Repositories\Interfaces\SupplierCategoryRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierCertRepositoryInterface;
use Encoda\Supplier\Repositories\Interfaces\SupplierRepositoryInterface;

class RepositoriesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( SupplierCategoryRepositoryInterface::class, SupplierCategoryRepository::class );
        $this->app->bind( SupplierRepositoryInterface::class, SupplierRepository::class );
        $this->app->bind( SupplierCertRepositoryInterface::class, SupplierCertRepository::class );

    }
}
