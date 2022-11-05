<?php
namespace Encoda\Notification\Channels;

use Encoda\Notification\Notifications\EcNotification;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class CustomDbChannel
{
    /**
     * @param                $notifiable
     * @param EcNotification $notification
     *
     * @return mixed
     */
    public function send($notifiable, EcNotification $notification)
    {
        $data = $notification->toDatabase($notifiable);
        /**
         * @var Model|null $object
         */
        $object = $notification->additionalData['object'] ?? null;
        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,

            //customize here
            'type' => $data['type'],
            'data' => $data['data'],
            'title' => $data['title'],
            'pinned' => $data['pinned'],
            'event_notification_id' => $data['event_notification_id'],
            'modelable_type' => is_a($object, Model::class) ? $object->getMorphClass(): null,
            'modelable_id' => $object?->id,
            'read_at' => null,
        ]);
    }
}
