<?php

namespace Encoda\Notification\Listeners;

use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Jobs\SendNotificationJob;
use Encoda\Notification\Models\EventNotification;
use ReflectionClass;
use ReflectionException;

/**
 *
 */
class SendModelNotification
{
    /**
     * @throws ReflectionException
     */
    public function handle($object, $actionType)
    {

        $eventNotifications = EventNotification::query()
            ->active()
            ->where('type', EventNotificationTypeEnum::AUTO->value)
            ->whereHas('rules', function ($query) use ($object, $actionType) {
                $query->where('model', $this->getObjectShortName($object))
                    ->where('action', $actionType->value);
            })->get();
        $additionalData = [
            'object' => $object,
            'action' => $actionType->value,
            'modelType' => $this->getObjectShortName($object)
        ];
        foreach ($eventNotifications as $eventNotification){
            dispatch(new SendNotificationJob($eventNotification, $additionalData));
        }
    }

    /**
     * @param $object
     *
     * @return string
     * @throws ReflectionException
     */
    public function getObjectShortName($object)
    {
        $reflect = new ReflectionClass($object);
        return $reflect->getShortName();
    }
}
