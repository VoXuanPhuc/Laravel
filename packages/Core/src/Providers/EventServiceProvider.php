<?php
namespace Encoda\Core\Providers;

use Encoda\Core\Listeners\LogResponseReceived;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot() {
        Event::listen(
            ResponseReceived::class,
            [
                LogResponseReceived::class, 'handle'
            ]
        );
    }
}
