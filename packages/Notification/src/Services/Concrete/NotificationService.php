<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Encoda\Notification\Services\Interfaces\NotificationServiceInterface;

/**
 *
 */
class NotificationService implements NotificationServiceInterface
{

    /**
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository

    )
    {
    }

    /**
     * @param $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getNotification($uid)
    {

        $notification = $this->notificationRepository->findByUid($uid);

        if (!$notification) {
            throw new NotFoundException('Notification not found');
        }

        return $notification;
    }

    /**
     * @return mixed
     */
    public function allNotifications()
    {
        $user = auth()->user();

        return $user->pinnedNotifications;
    }

    /**
     * @param $uid
     *
     * @return void
     * @throws NotFoundException
     */
    public function markNotificationAsRead($uid)
    {
        $notification = $this->getNotification($uid);

        $notification->markAsRead();
    }
}
