<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Notification\DTOs\DashboardNotificationDTO;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Encoda\Notification\Services\Interfaces\DashboardNotificationServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class DashboardNotificationService implements DashboardNotificationServiceInterface
{

    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository

    )
    {
    }


    public function getNotifications()
    {
        $user = auth()->user();

        /** @var Collection $pinnedNotifications */
        $pinnedNotifications = $user->pinnedNotifications;

        /** @var Collection $newestNotifications */
        $newestNotifications = $user->newestNotifications( 'DESC', $pinnedNotifications )->getResults();

        return (new DashboardNotificationDTO())
            ->withPinnedNotifications( $pinnedNotifications )
            ->withNewestNotifications( $newestNotifications )
            ->withUnreadCount( $user->unreadNotifications->count() )
            ;
    }
}
