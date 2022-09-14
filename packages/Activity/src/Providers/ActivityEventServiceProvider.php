<?php

namespace Encoda\Activity\Providers;

use Encoda\Activity\Listeners\CreateActivity;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ActivityEventServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Event::listen('activity.create.after', CreateActivity::class . '@handle' );
    }
}
