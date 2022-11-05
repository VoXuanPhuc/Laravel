<?php

namespace Encoda\Notification\Models;

use Encoda\Core\Models\Model;

class EventNotificationRule extends Model
{
    protected $table = 'event_notification_rules';

    protected $guarded = ['id'];
    protected $hidden = ['id', 'event_notification_id'];
}
