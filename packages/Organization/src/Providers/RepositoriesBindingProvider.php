<?php

namespace Encoda\Organization\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\Organization\Repositories\Concrete\BusinessUnitRepository;
use Encoda\Organization\Repositories\Concrete\DivisionRepository;
use Encoda\Organization\Repositories\Concrete\IndustryRepository;
use Encoda\Organization\Repositories\Interfaces\BusinessUnitRepositoryInterface;
use Encoda\Organization\Repositories\Interfaces\DivisionRepositoryInterface;
use Encoda\Organization\Repositories\Interfaces\IndustryRepositoryInterface;
use Encoda\Organization\Repositories\Interfaces\OrganizationRepositoryInterface;
use Encoda\Organization\Repositories\Concrete\OrganizationRepository;


class RepositoriesBindingProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind( OrganizationRepositoryInterface::class, OrganizationRepository::class );
        $this->app->bind( DivisionRepositoryInterface::class, DivisionRepository::class );
        $this->app->bind( BusinessUnitRepositoryInterface::class, BusinessUnitRepository::class );
        $this->app->bind( IndustryRepositoryInterface::class, IndustryRepository::class );
    }
}
