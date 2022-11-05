<?php

namespace Encoda\Notification\Traits;

use Encoda\Notification\Enums\EventNotificationRuleActionEnum;
use Illuminate\Support\Facades\Event;

trait NotifySender
{
    public static function bootNotifySender()
    {

        static::created(static function ($model) {
            Event::dispatch('model.trigger.notify', [
                'model'  => $model,
                'action' => EventNotificationRuleActionEnum::CREATE
            ]);
        });


        static::updated(static function ($model) {
            Event::dispatch('model.trigger.notify', [
                'model'  => $model,
                'action' => EventNotificationRuleActionEnum::UPDATE
            ]);
        });

        static::deleted(static function ($model) {
            Event::dispatch('model.trigger.notify', [
                'model'  => $model,
                'action' => EventNotificationRuleActionEnum::DELETE
            ]);
        });
    }

}
