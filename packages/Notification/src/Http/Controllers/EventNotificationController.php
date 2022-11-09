<?php

namespace Encoda\Notification\Http\Controllers;

use Encoda\Notification\Http\Requests\EventNotification\CreateEventNotificationRequest;
use Encoda\Notification\Http\Requests\EventNotification\UpdateEventNotificationRequest;
use Encoda\Notification\Services\Interfaces\EventNotificationServiceInterface;

/**
 *
 */
class EventNotificationController extends Controller
{
    /**
     * @param EventNotificationServiceInterface $eventNotificationService
     */
    public function __construct(
        protected EventNotificationServiceInterface $eventNotificationService
    )
    {
    }

    /**
     * @return mixed
     */
    public function configs()
    {
        return $this->eventNotificationService->buildConfigs();
    }

    /**
     * @return mixed
     */
    public function configByModule(string $module)
    {
        return $this->eventNotificationService->buildConfigByModule($module);
    }

    /**
     * @return mixed
     */
    public function list()
    {
        return $this->eventNotificationService->list();
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function detail(string $uid)
    {
        return $this->eventNotificationService->getEventNotification($uid);
    }


    /**
     * @param CreateEventNotificationRequest $request
     *
     * @return mixed
     */
    public function create(CreateEventNotificationRequest $request)
    {
        return $this->eventNotificationService->create($request);
    }

    /**
     * @param UpdateEventNotificationRequest $request
     * @param string                         $uid
     *
     * @return mixed
     */
    public function update(UpdateEventNotificationRequest $request, string $uid)
    {
        return $this->eventNotificationService->update($request, $uid);
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function delete(string $uid)
    {
        return $this->eventNotificationService->delete($uid);
    }
}
