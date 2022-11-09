<?php

namespace Encoda\Notification\Traits;


use Encoda\Notification\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;

trait EcNotifiable
{
    use Notifiable;

    public function routeNotificationForCustomDatabase()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }
}
