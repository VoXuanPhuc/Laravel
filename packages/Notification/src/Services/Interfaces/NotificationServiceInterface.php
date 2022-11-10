<?php

namespace Encoda\Notification\Services\Interfaces;

interface NotificationServiceInterface
{


    public function list();

    public function allNotifications();

    public function markNotificationAsRead($uid);

}
