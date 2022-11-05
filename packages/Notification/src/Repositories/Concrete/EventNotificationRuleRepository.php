<?php

namespace Encoda\Notification\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Notification\Models\EventNotificationRule;
use Encoda\Notification\Repositories\Interfaces\EventNotificationRuleRepositoryInterface;

class EventNotificationRuleRepository extends Repository implements EventNotificationRuleRepositoryInterface
{

    /**
     * @return string
     */
    public function model(): string
    {
        return EventNotificationRule::class;
    }
}
