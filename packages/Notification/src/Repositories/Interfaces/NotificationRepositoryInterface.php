<?php

namespace Encoda\Notification\Repositories\Interfaces;

use Encoda\Core\Interfaces\RepositoryInterface;

interface NotificationRepositoryInterface extends RepositoryInterface
{

    public function getUserPinnedNotifications( $user );

    public function getUserNewestNotification( $user );

    public function getUserUnreadNotifications( $user );
}
