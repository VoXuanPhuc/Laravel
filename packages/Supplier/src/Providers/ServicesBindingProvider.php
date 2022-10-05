<?php

namespace Encoda\Supplier\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Supplier\Services\Concrete\SupplierCategoryRepository;
use Encoda\Supplier\Services\Concrete\SupplierCategoryService;
use Encoda\Supplier\Services\Concrete\SupplierCertService;
use Encoda\Supplier\Services\Concrete\SupplierRepository;
use Encoda\Supplier\Services\Concrete\SupplierService;
use Encoda\Supplier\Services\Interfaces\SupplierCategoryServiceInterface;
use Encoda\Supplier\Services\Interfaces\SupplierCertServiceInterface;
use Encoda\Supplier\Services\Interfaces\SupplierServiceInterface;

class ServicesBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( SupplierCategoryServiceInterface::class, SupplierCategoryService::class );
        $this->app->bind( SupplierServiceInterface::class, SupplierService::class );
        $this->app->bind( SupplierCertServiceInterface::class, SupplierCertService::class );

    }
}
