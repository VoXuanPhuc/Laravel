<?php

namespace Encoda\Notification\Traits;

use Encoda\Notification\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait EcHasNotificationTrait
{


    /**
     * @param string $sort
     * @return mixed
     */
    public function pinnedNotifications( $sort = 'DESC' ) {

        return $this->morphMany( Notification::class, 'notifiable' )
            ->orderBy('created_at', $sort )
            ->wherePinned(true)
            ->whereNull('read_at')
            ->take( config('notification.dashboard.newest') ?: 1  )
            ;
    }

    /**
     * @param string $sort
     * @param Collection|null $toIgnoreNotifications
     * @return mixed
     */
    public function newestNotifications(string $sort = 'DESC', Collection $toIgnoreNotifications = null ): mixed
    {

        $toIgnoreNotificationIds = $toIgnoreNotifications->map( function( $item ) {
            return $item->id;
        });

        return $this->morphMany( Notification::class, 'notifiable' )
            ->orderBy('created_at', $sort )
            ->whereNotIn( 'id', $toIgnoreNotificationIds )
            ->whereNull('read_at')
            ->take(config('notification.dashboard.newest') ?: 2 )
            ;
    }

}
