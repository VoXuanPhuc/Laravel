<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\Notification\Models\Notification;
use Encoda\Notification\Repositories\Interfaces\NotificationRepositoryInterface;
use Encoda\Notification\Services\Interfaces\NotificationServiceInterface;
use Illuminate\Validation\ValidationException;

/**
 *
 */
class NotificationService implements NotificationServiceInterface
{

    /**
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository

    )
    {
    }


    /**
     * @return mixed
     * @throws ValidationException
     */
    public function list()
    {
        $query = $this->notificationRepository->query();

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return  $this->notificationRepository->applyPaging($query);

    }


    /**
     * @param $query
     * @throws ValidationException
     */
    public function applySearchFilter($query): void
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(Notification::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['search', 'title', 'data',])
            ->setCustomFilter('search', static function ($query, $type, $column, $value) {
                // Group where name like and description like
                $query->where( function( $query) use ( $value) {
                    $query->where('title', 'LIKE', '%' . $value . '%')
                        ->orWhere('data', 'LIKE', '%' . $value . '%');
                });

            })
            ->validate()
            ->applyFilter()
        ;
    }

    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySortFilter($query): void
    {
        //Apply sort
        SortFluent::init()
            ->setTable(Notification::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'title', ])
            ->validate()
            ->applySort();
    }

    /**
     * @param $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getNotification($uid)
    {

        $notification = $this->notificationRepository->find( $uid );

        if (!$notification) {
            throw new NotFoundException('Notification not found');
        }

        return $notification;
    }

    /**
     * @return mixed
     */
    public function allNotifications()
    {
        $user = auth()->user();
        $pinnedNotifications = $user->pinnedNotifications;
        $pinnedNotifications->map(function ($item){
            $item->data = stripslashes($item->data);
            return $item;
        });
        return $pinnedNotifications;
    }

    /**
     * @param $uid
     *
     * @return void
     * @throws NotFoundException
     */
    public function markNotificationAsRead($uid)
    {
        $notification = $this->getNotification($uid);

        $notification->markAsRead();
    }
}
