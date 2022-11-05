<?php

namespace Encoda\Notification\Enums;

enum EventNotificationStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
}
