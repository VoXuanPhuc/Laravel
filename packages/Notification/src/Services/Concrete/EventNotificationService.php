<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Activity\Models\Activity;
use Encoda\Activity\Models\Equipment;
use Encoda\Core\Exceptions\BaseException;
use Encoda\Core\Exceptions\ForbiddenHttpException;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FileHelper;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\EDocs\Models\Document;
use Encoda\EDocs\Repositories\Interfaces\DocumentRepositoryInterface;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Encoda\Identity\Models\Database\User;
use Encoda\Identity\Repositories\Concrete\Database\UserRepository;
use Encoda\Notification\DTOs\EventNotificationModuleConfigDTO;
use Encoda\Notification\DTOs\EventNotificationSettingDTO;
use Encoda\Notification\Enums\EventNotificationStatusEnum;
use Encoda\Notification\Http\Requests\EventNotification\CreateEventNotificationRequest;
use Encoda\Notification\Http\Requests\EventNotification\UpdateEventNotificationRequest;
use Encoda\Notification\Jobs\SendNotificationJob;
use Encoda\Notification\Models\EventNotification;
use Encoda\Notification\Models\EventNotificationRule;
use Encoda\Notification\Repositories\Interfaces\EventNotificationRepositoryInterface;
use Encoda\Notification\Services\Interfaces\EventNotificationServiceInterface;
use Encoda\Notification\Enums\EventNotificationMethodEnum;
use Encoda\Notification\Enums\EventNotificationRuleActionEnum;
use Encoda\Notification\Enums\EventNotificationRuleModelEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Organization\Models\BusinessUnit;
use Encoda\Organization\Models\Division;
use Encoda\Supplier\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 *
 */
class EventNotificationService implements EventNotificationServiceInterface
{
    /**
     * @param EventNotificationRepositoryInterface $eventNotificationRepository
     * @param UserRepository                       $userRepository
     * @param DocumentServiceInterface             $documentService
     * @param DocumentRepositoryInterface          $documentRepository
     */
    public function __construct(
        protected EventNotificationRepositoryInterface $eventNotificationRepository,
        protected UserRepository                       $userRepository,
        protected DocumentServiceInterface             $documentService,
        protected DocumentRepositoryInterface          $documentRepository,
    )
    {
    }

    /**
     * @return EventNotificationSettingDTO
     */
    public function buildConfigs(): EventNotificationSettingDTO
    {

        $eventNotificationSettingDTO = new EventNotificationSettingDTO();
        $eventNotificationSettingDTO->type = array_column(EventNotificationTypeEnum::cases(), 'value');
        $eventNotificationSettingDTO->method = array_column(EventNotificationMethodEnum::cases(), 'value');
        $eventNotificationSettingDTO->ruleAction = array_column(EventNotificationRuleActionEnum::cases(), 'value');
        $eventNotificationSettingDTO->ruleModel = array_column(EventNotificationRuleModelEnum::cases(), 'value');
        $eventNotificationSettingDTO->replacements = [
            EventNotificationTypeEnum::MANUAL->value => $this->getReplacementFields(EventNotificationTypeEnum::MANUAL),
            EventNotificationTypeEnum::AUTO->value   => $this->getReplacementFields(EventNotificationTypeEnum::AUTO),
        ];


        return $eventNotificationSettingDTO;
    }

    public function buildConfigByModule(string $module)
    {
        Validator::make([
            'module' => $module
        ] ,[
            'module' => [Rule::in(values: array_column(EventNotificationRuleModelEnum::cases(), 'value'))],
        ])->validate();
        $eventNotificationModuleConfigDTO = new EventNotificationModuleConfigDTO();
        $eventNotificationModuleConfigDTO->replacements = [
            $module => call_user_func([$this->getModelFromEventNotificationEnum(EventNotificationRuleModelEnum::tryFrom($module)), 'getModelAllowedAttribute'])
        ];
        return $eventNotificationModuleConfigDTO;
    }

    /**
     * @param EventNotificationRuleModelEnum $eventNotificationRuleModelEnum
     *
     * @return string
     */
    public function getModelFromEventNotificationEnum(EventNotificationRuleModelEnum $eventNotificationRuleModelEnum): string
    {
        return match ($eventNotificationRuleModelEnum) {
            EventNotificationRuleModelEnum::USER => User::class,
            EventNotificationRuleModelEnum::ACTIVITY => Activity::class,
            EventNotificationRuleModelEnum::BUSINESS_UNIT => BusinessUnit::class,
            EventNotificationRuleModelEnum::DIVISION => Division::class,
            EventNotificationRuleModelEnum::EQUIPMENT => Equipment::class,
            EventNotificationRuleModelEnum::SUPPLIER => Supplier::class,
        };

    }

    /**
     * @param string $uid
     *
     * @return EventNotification
     * @throws NotFoundException
     */
    public function getEventNotification(string $uid): EventNotification
    {
        $eventNotification = $this->eventNotificationRepository->findByUid($uid);
        if (!$eventNotification) {
            throw new NotFoundException('Event notification not found');
        }

        return $eventNotification->fresh()->load(['rules', 'users']);
    }

    /**
     * @return LengthAwarePaginator
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function list()
    {
        $query = tenant()
            ->eventNotifications()
            ->with(['rules', 'users']);

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return
            $this->eventNotificationRepository->applyPaging($query);

    }


    /**
     * @param $query
     *
     * @return void
     * @throws ValidationException
     */
    public function applySearchFilter($query): void
    {
        //Apply filter
        FilterFluent::init()
            ->setTable(EventNotification::getTableName())
            ->setQuery($query)
            ->setAllowedFilters(['name', 'title', 'description', 'data'])
            ->validate()
            ->applyFilter();
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
            ->setTable(EventNotification::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'title', 'description', 'data'])
            ->validate()
            ->applySort();
    }


    /**
     * @throws ServerErrorException|BaseException
     */
    public function create(CreateEventNotificationRequest $request): ?EventNotification
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            /**
             * @var EventNotification $eventNotification
             */
            if (!$request->get('dispatch_after')) {
                $data['dispatch_after'] = null;
            }
            $eventNotification = $this->eventNotificationRepository->newInstance($data);
            $eventNotification->status = EventNotificationStatusEnum::NEW;

            $eventNotification->save();

            //Event notification Only have rule when type is auto
            if ($request->get('type') === EventNotificationTypeEnum::AUTO->value) {
                $eventNotificationRules = collect($request->get('rules'))->map(function ($items) {
                    return new EventNotificationRule([
                        'model'  => $items['model'],
                        'action' => $items['action'],
                    ]);
                });
                $eventNotification->rules()->saveMany($eventNotificationRules);

            }
            if (!$request->get('all_user')) {
                $userIds = $this->userRepository
                    ->query()
                    ->whereIn('uid', collect($request->get('receivers'))->pluck('uid'))
                    ->get()->pluck('id');
                $eventNotification->users()->syncWithoutDetaching($userIds);
            }

            if ($request->has('attachments')) {
                $attachments = $request->get('attachments');
                foreach ($attachments as $attachment) {
                    $document = $this->documentService->getDocument($attachment['uid'] ?? null);
                    $eventNotification->addDocument($document, 'attachments');
                }
            }
            $eventNotification = $eventNotification->fresh();
            //Dispatch immediate if dispatch_after is null
            if (!$request->get('dispatch_after')
                && $request->get('type') === EventNotificationTypeEnum::MANUAL->value
                && $this->canSendNotification($eventNotification)
            ) {
                dispatch(new SendNotificationJob($eventNotification));
            }
            DB::commit();

        } catch (BaseException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create event notification error');
        }

        return $eventNotification?->load(['rules', 'users']);
    }

    /**
     * @param UpdateEventNotificationRequest $request
     * @param string                         $uid
     *
     * @return EventNotification|null
     * @throws BaseException
     * @throws ForbiddenHttpException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function update(UpdateEventNotificationRequest $request, string $uid): ?EventNotification
    {
        try {
            DB::beginTransaction();
            $eventNotification = $this->getEventNotification($uid);
            if ($eventNotification->status !== EventNotificationStatusEnum::NEW) {
                throw new ForbiddenHttpException("Only allow update on status new");
            }

            $data = $request->all();
            if (!$request->get('dispatch_after')) {
                $data['dispatch_after'] = null;
            }
            /**
             * @var EventNotification $eventNotification
             */
            $eventNotification->update($data);

            //Clear data before re-update
            $eventNotification->rules()->delete();
            $eventNotification->users()->detach();

            $eventNotification->clearDocumentCollection('attachments');

            //Only have rule when type is auto
            if ($request->get('type') === EventNotificationTypeEnum::AUTO->value) {
                $eventNotificationRules = collect($request->get('rules'))->map(function ($items) {
                    return new EventNotificationRule([
                        'model'  => $items['model'],
                        'action' => $items['action'],
                    ]);
                });
                $eventNotification->rules()->saveMany($eventNotificationRules);

            }
            if (!$request->get('all_user')) {
                $userIds = $this->userRepository
                    ->query()
                    ->whereIn('uid', collect($request->get('receivers'))->pluck('uid'))
                    ->get()->pluck('id');
                $eventNotification->users()->syncWithoutDetaching($userIds);
            }

            if ($request->has('attachments')) {
                $attachments = $request->get('attachments');
                foreach ($attachments as $attachment) {
                    $document = $this->documentService->getDocument($attachment['uid'] ?? null);
                    $eventNotification->addDocument($document, 'attachments');
                }
            }
            $eventNotification = $eventNotification->fresh();
            //Dispatch immediate if dispatch_after is null
            if (!$request->get('dispatch_after')
                && $request->get('type') === EventNotificationTypeEnum::MANUAL->value
                && $this->canSendNotification($eventNotification)
            ) {
                dispatch(new SendNotificationJob($eventNotification));
            }
            DB::commit();

        } catch (BaseException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create event notification error');
        }

        return $eventNotification?->load(['rules', 'users']);
    }

    /**
     * @param string $uid
     *
     * @return bool|null
     * @throws NotFoundException
     */
    public function delete(string $uid)
    {
        $eventNotification = $this->getEventNotification($uid);
        $eventNotification->clearDocumentCollection('attachments');
        return $eventNotification->delete();
    }

    /**
     * @param EventNotification $eventNotification
     *
     * @return bool
     */
    public function canSendNotification(EventNotification $eventNotification): bool
    {
        if (!$eventNotification->is_active) {
            return false;
        }
        if ($eventNotification->type === EventNotificationTypeEnum::MANUAL &&
            $eventNotification->status !== EventNotificationStatusEnum::NEW) {
            return false;
        }
        return true;
    }

    /**
     * @param EventNotificationTypeEnum $type
     *
     * @return array
     */
    protected function getReplacementFields(EventNotificationTypeEnum $type): array
    {
        $replacements = [
            "user"  => User::getModelAllowedAttribute(),
            "owner" => User::getModelAllowedAttribute(),
        ];
        if ($type === EventNotificationTypeEnum::AUTO) {
            $replacements = array_merge($replacements, [
                "general" => [
                    "action",
                    "modelType",
                ],
                "object"  => [
                    "name"
                ]
            ]);
        }
        return $replacements;
    }

}
