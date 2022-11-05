<?php

namespace Encoda\Notification\Jobs;

use App\Jobs\Job;
use Carbon\Carbon;
use Encoda\EDocs\Models\Document;
use Encoda\Identity\Models\Database\User;
use Encoda\MultiTenancy\Models\Tenant;
use Encoda\Notification\Enums\EventNotificationStatusEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Models\EventNotification;
use Encoda\Notification\Notifications\EcNotification;
use Encoda\Notification\Services\Interfaces\EventNotificationServiceInterface;
use Encoda\Organization\Models\Organization;
use Illuminate\Mail\Attachment;

/**
 *
 */
class SendNotificationJob extends Job
{
    /**
     *
     */
    public function __construct(
        protected EventNotification $eventNotification,
        protected                   $additionalData = null
    )
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventNotificationService = app(EventNotificationServiceInterface::class);
        $tenant = $this->eventNotification->organization;
        /**
         * @var Organization $tenant
         */
        $tenant->makeCurrent();
        if ($eventNotificationService->canSendNotification($this->eventNotification)) {
            $this->updateEventNotificationStatus(EventNotificationStatusEnum::IN_PROGRESS);

            $this->eventNotification->load(['owner', 'users']);
            $attachments = $this->buildAttachments();
            foreach ($this->eventNotification->users as $user) {
                /**
                 * @var User $user
                 */
                $user->notify(new EcNotification($this->eventNotification, $attachments, $this->additionalData));
            }
            $this->updateEventNotificationStatus(EventNotificationStatusEnum::DONE);
        }
    }


    /**
     * @return array
     */
    public function buildAttachments()
    {
        $attachments = [];
        foreach ($this->eventNotification->getDocuments('attachments') as $document) {
            /**
             * @var Document $document
             */
            $attachments[] = Attachment::fromStorageDisk('s3', $document->path);
        }
        return $attachments;
    }

    /**
     * @param EventNotificationStatusEnum $status
     *
     * @return void
     */
    public function updateEventNotificationStatus(EventNotificationStatusEnum $status)
    {
        if ($this->eventNotification->type === EventNotificationTypeEnum::MANUAL) {
            $this->eventNotification->status = $status;
            $this->eventNotification->save();
        }
    }
}
