<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Notification\DTOs\DashboardNotificationDTO;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Encoda\Notification\Services\Interfaces\NotificationServiceInterface;

class NotificationService implements NotificationServiceInterface
{

    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository

    )
    {
    }


    public function allNotifications()
    {
        $user = auth()->user();

        return $user->pinnedNotifications;
    }
}
