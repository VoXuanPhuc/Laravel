<?php

namespace Encoda\Notification\Providers;

use Encoda\Identity\Listeners\CreateCognitoUser;
use Encoda\Notification\Listeners\SendModelNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class EventServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {

        Event::listen('model.trigger.notify', SendModelNotification::class . '@handle');
    }

    /**
     * @return void
     */
    public function register()
    {

    }
}
