<?php

namespace Encoda\Notification\Http\Controllers;

use Encoda\Notification\Services\Interfaces\DashboardNotificationServiceInterface;

class DashboardNotificationController extends Controller
{

    public function __construct(
        protected DashboardNotificationServiceInterface $dashboardNotificationService

    )
    {
    }

    /**
     * @return mixed
     */
    public function index() {

        return $this->dashboardNotificationService->getNotifications();
    }
}
