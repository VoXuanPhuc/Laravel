<?php

namespace Encoda\EDocs\Providers;

use Carbon\Laravel\ServiceProvider;
use Encoda\EDocs\Repositories\Concrete\DocumentRepository;
use Encoda\EDocs\Repositories\Interfaces\DocumentRepositoryInterface;


/**
 *
 */
class DocumentRepositoryBindingProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind( DocumentRepositoryInterface::class, DocumentRepository::class );
    }
}
