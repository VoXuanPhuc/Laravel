<?php

namespace Encoda\Identity\Traits;

use Encoda\Notification\Enums\EventNotificationRuleActionEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

trait HasOwner
{
    public static function bootHasOwner()
    {
        static::creating(static function ($model) {
            if( app()->runningInConsole()) {
                return;
            }
            $model->created_by = Auth::user()?->id;
        });
    }
}
