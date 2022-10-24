<?php

namespace Encoda\BCP\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\BCP\Repositories\Concrete\BCPRepository;
use Encoda\BCP\Repositories\Interfaces\BCPRepositoryInterface;

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
        $this->app->bind(BCPRepositoryInterface::class, BCPRepository::class);
    }
}
