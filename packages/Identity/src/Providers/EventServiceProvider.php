<?php

namespace Encoda\Identity\Providers;

use Encoda\Identity\Listeners\CreateCognitoUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    public function boot() {

        Event::listen('identity.cognito.user.create.after', CreateCognitoUser::class . '@handle' );
    }

    public function register()
    {

    }
}
