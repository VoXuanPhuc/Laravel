<?php

namespace Encoda\Notification\Http\Controllers;

use Encoda\Notification\Services\Interfaces\NotificationServiceInterface;

class NotificationController extends Controller
{

    public function __construct(
        protected NotificationServiceInterface $notificationService

    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->notificationService->allNotifications();
    }
}
