<?php

namespace Encoda\Notification\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Notification\Models\EventNotificationReceiver;
use Encoda\Notification\Repositories\Interfaces\EventNotificationReceiverRepositoryInterface;

/**
 *
 */
class EventNotificationReceiverRepository extends Repository implements EventNotificationReceiverRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return EventNotificationReceiver::class;
    }
}
