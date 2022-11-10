<?php

namespace Encoda\Notification\Http\Controllers;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Notification\Models\Notification;
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

        return $this->notificationService->list();
    }

    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function detail( $uid ) {

        /** @var Notification $notification */
        $notification = $this->notificationService->getNotification( $uid );

        return $notification->load('notifiable');
    }

    /**
     * @return mixed
     */
    public function markAsRead( $uid ) {

        return $this->notificationService->markNotificationAsRead( $uid );
    }
}
