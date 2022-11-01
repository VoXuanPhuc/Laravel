<?php

namespace Encoda\Notification\Providers;

use Encoda\Notification\Repositories\Concrete\NotificationRepository;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryBindingProvider extends ServiceProvider
{


    public function register()
    {
        $this->app->bind( NotificationRepositoryInterface::class, NotificationRepository::class );
    }
}
