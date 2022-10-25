<?php

namespace Encoda\BIA\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\BIA\Repositories\Concrete\BIARepository;
use Encoda\BIA\Repositories\Interfaces\BIARepositoryInterface;

/**
 *
 */
class RepositoriesBindingProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->app->bind(BIARepositoryInterface::class, BIARepository::class);
    }
}
