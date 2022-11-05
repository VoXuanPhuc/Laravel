<?php

namespace Encoda\Notification\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Notification\Models\EventNotification;
use Encoda\Notification\Repositories\Interfaces\EventNotificationRepositoryInterface;

class EventNotificationRepository extends Repository implements EventNotificationRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return EventNotification::class;
    }
}
