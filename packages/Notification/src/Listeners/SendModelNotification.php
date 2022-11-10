<?php

namespace Encoda\Notification\Listeners;

use Encoda\Core\Helpers\ObjectHelper;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Jobs\SendNotificationJob;
use Encoda\Notification\Models\EventNotification;
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
                $query->where('model', ObjectHelper::getObjectShortName($object))
                    ->where('action', $actionType->value);
            })->get();
        $additionalData = [
            'object' => $object,
            'action' => $actionType->value,
            'modelType' => ObjectHelper::getObjectShortName($object)
        ];
        foreach ($eventNotifications as $eventNotification){
            dispatch(new SendNotificationJob($eventNotification, $additionalData));
        }
    }

}
