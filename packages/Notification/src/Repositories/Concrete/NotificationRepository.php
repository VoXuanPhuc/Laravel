<?php

namespace Encoda\Notification\Repositories\Concrete;

use Encoda\Core\Eloquent\Repository;
use Encoda\Notification\Models\Notification;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;

class NotificationRepository extends Repository implements NotificationRepositoryInterface
{

    public function model()
    {
        return Notification::class;
    }

    public function getUserPinnedNotifications($user)
    {
    }

    public function getUserNewestNotification($user)
    {
        // TODO: Implement getUserNewestNotification() method.
    }

    public function getUserUnreadNotifications($user)
    {
        // TODO: Implement getUserUnreadNotifications() method.
    }
}
