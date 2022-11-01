<?php

namespace Encoda\Notification\DTOs;

use Illuminate\Database\Eloquent\Collection;

class DashboardNotificationDTO extends BaseDTO
{

    public $pinnedNotifications;
    public $newestNotifications;
    public $unreadCount;

    /**
     * @param Collection $notifications
     * @return DashboardNotificationDTO
     */
    public function withPinnedNotifications( Collection $notifications ) {
        $this->pinnedNotifications = $notifications->toArray();

        return $this;
    }

    /***
     * @param Collection $notifications
     * @return DashboardNotificationDTO
     */
    public function withNewestNotifications( Collection $notifications ) {
        $this->newestNotifications = $notifications->toArray();

        return $this;
    }

    /**
     * @param $unreadCnt
     * @return DashboardNotificationDTO
     */
    public function withUnreadCount( $unreadCnt ) {
        $this->unreadCount = $unreadCnt;

        return $this;
    }
}
