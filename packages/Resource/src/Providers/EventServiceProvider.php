<?php

namespace Encoda\Resource\Providers;

use Encoda\Resource\Listeners\CreateResourceOwner;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    public function boot() {
        Event::listen('resource.owner.create.after', CreateResourceOwner::class . '@handle' );
    }
}
