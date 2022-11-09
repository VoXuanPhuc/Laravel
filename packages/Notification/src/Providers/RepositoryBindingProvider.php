<?php

namespace Encoda\Notification\Providers;

use Encoda\Notification\Repositories\Concrete\EmailTemplateRepository;
use Encoda\Notification\Repositories\Concrete\EventNotificationReceiverRepository;
use Encoda\Notification\Repositories\Interfaces\EmailTemplateRepositoryInterface;
use Encoda\Notification\Repositories\Interfaces\EventNotificationReceiverRepositoryInterface;
use Encoda\Notification\Repositories\Concrete\EventNotificationRepository;
use Encoda\Notification\Repositories\Interfaces\EventNotificationRepositoryInterface;
use Encoda\Notification\Repositories\Concrete\EventNotificationRuleRepository;
use Encoda\Notification\Repositories\Interfaces\EventNotificationRuleRepositoryInterface;
use Encoda\Notification\Repositories\Concrete\NotificationRepository;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 *
 */
class RepositoryBindingProvider extends ServiceProvider
{


    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(EventNotificationRepositoryInterface::class, EventNotificationRepository::class);
        $this->app->bind(EventNotificationRuleRepositoryInterface::class, EventNotificationRuleRepository::class);
        $this->app->bind(EventNotificationReceiverRepositoryInterface::class, EventNotificationReceiverRepository::class);
        $this->app->bind(EmailTemplateRepositoryInterface::class, EmailTemplateRepository::class);
    }
}
