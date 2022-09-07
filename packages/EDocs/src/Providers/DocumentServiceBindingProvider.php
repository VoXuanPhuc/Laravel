<?php

namespace Encoda\EDocs\Providers;

use Encoda\EDocs\Services\Concrete\DocumentService;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Illuminate\Support\ServiceProvider;

class DocumentServiceBindingProvider extends ServiceProvider
{

    public function register()
    {

        $this->app->bind( DocumentServiceInterface::class, DocumentService::class );
    }
}
