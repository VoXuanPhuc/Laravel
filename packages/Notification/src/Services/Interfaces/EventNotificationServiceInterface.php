<?php

namespace Encoda\Notification\Services\Interfaces;

use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Http\Requests\EventNotification\CreateEventNotificationRequest;
use Encoda\Notification\Http\Requests\EventNotification\UpdateEventNotificationRequest;
use Encoda\Notification\Models\EventNotification;

interface EventNotificationServiceInterface
{
    public function buildConfigs();
    public function buildConfigByModule(string $module);
    public function list();
    public function getEventNotification(string $uid);
    public function create(CreateEventNotificationRequest $request);
    public function update(UpdateEventNotificationRequest $request, string $uid);
    public function delete(string $uid);
    public function canSendNotification(EventNotification $eventNotification);
}
