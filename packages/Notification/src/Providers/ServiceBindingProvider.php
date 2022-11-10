<?php

namespace Encoda\Notification\Providers;

use Encoda\Notification\Services\Concrete\DashboardNotificationService;
use Encoda\Notification\Services\Concrete\EmailTemplateService;
use Encoda\Notification\Services\Concrete\EventNotificationService;
use Encoda\Notification\Services\Concrete\NotificationService;
use Encoda\Notification\Services\Interfaces\DashboardNotificationServiceInterface;
use Encoda\Notification\Services\Interfaces\EmailTemplateServiceInterface;
use Encoda\Notification\Services\Interfaces\EventNotificationServiceInterface;
use Encoda\Notification\Services\Interfaces\NotificationServiceInterface;

class ServiceBindingProvider extends \Illuminate\Support\ServiceProvider
{


    public function register()
    {
        $this->app->bind( NotificationServiceInterface::class, NotificationService::class );
        $this->app->bind( DashboardNotificationServiceInterface::class, DashboardNotificationService::class );
        $this->app->bind( EventNotificationServiceInterface::class, EventNotificationService::class );
        $this->app->bind( EmailTemplateServiceInterface::class, EmailTemplateService::class );
    }
}