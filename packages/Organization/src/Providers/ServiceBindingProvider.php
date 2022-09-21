<?php

namespace Encoda\Organization\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Organization\Services\Concrete\BusinessUnitService;
use Encoda\Organization\Services\Concrete\DivisionService;
use Encoda\Organization\Services\Concrete\IndustryService;
use Encoda\Organization\Services\Concrete\OrganizationService;
use Encoda\Organization\Services\Interfaces\BusinessUnitServiceInterface;
use Encoda\Organization\Services\Interfaces\DivisionServiceInterface;
use Encoda\Organization\Services\Interfaces\IndustryServiceInterface;
use Encoda\Organization\Services\Interfaces\OrganizationServiceInterface;

class ServiceBindingProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind( OrganizationServiceInterface::class, OrganizationService::class );
        $this->app->bind( DivisionServiceInterface::class, DivisionService::class );
        $this->app->bind( BusinessUnitServiceInterface::class, BusinessUnitService::class );
        $this->app->bind( IndustryServiceInterface::class, IndustryService::class );
    }
}
