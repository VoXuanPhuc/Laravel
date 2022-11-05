<?php
namespace Encoda\Notification\Console\Commands;

use Carbon\Carbon;
use Encoda\Notification\Enums\EventNotificationStatusEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Jobs\SendNotificationJob;
use Encoda\Notification\Models\EventNotification;
use Encoda\Notification\Services\Interfaces\EventNotificationServiceInterface;
use Encoda\Organization\Models\Organization;
use Illuminate\Console\Command;
use Throwable;

class SendNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encoda:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and send notification';


    public function __construct(
        protected EventNotificationServiceInterface $eventNotificationService,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle()
    {
        try{
            $eventNotifications = EventNotification::query()
                ->active()
                ->where('type', EventNotificationTypeEnum::MANUAL->value)
                ->where('status', EventNotificationStatusEnum::NEW->value)
                ->where('dispatch_after', "<=", Carbon::now())
                ->get();

            $this->info('Start sending notification');
            foreach ($eventNotifications as $eventNotification){
                $tenant = $eventNotification->organization;
                /**
                 * @var Organization $tenant
                 */
                $tenant->makeCurrent();
                $this->info(sprintf('Sending %s notification', $eventNotification->name));
                dispatch(new SendNotificationJob($eventNotification));
            }
            $this->info('All notifications have been sent');
        }catch (Throwable $e){
            $this->error('Something went wrong');
            throw $e;
        }

    }
}
