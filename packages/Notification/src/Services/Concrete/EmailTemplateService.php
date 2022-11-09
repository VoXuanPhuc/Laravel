<?php

namespace Encoda\Notification\Services\Concrete;

use Encoda\Core\Exceptions\BaseException;
use Encoda\Core\Exceptions\NotFoundException;
use Encoda\Core\Exceptions\ServerErrorException;
use Encoda\Core\Helpers\FilterFluent;
use Encoda\Core\Helpers\SortFluent;
use Encoda\EDocs\Services\Interfaces\DocumentServiceInterface;
use Encoda\Notification\Enums\EventNotificationStatusEnum;
use Encoda\Notification\Enums\EventNotificationTypeEnum;
use Encoda\Notification\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use Encoda\Notification\Http\Requests\EmailTemplate\UpdateEmailTemplateRequest;
use Encoda\Notification\Jobs\SendNotificationJob;
use Encoda\Notification\Models\EmailTemplate;
use Encoda\Notification\Models\EventNotification;
use Encoda\Notification\Models\EventNotificationRule;
use Encoda\Notification\Repositories\Interfaces\EmailTemplateRepositoryInterface;
use Encoda\Notification\Services\Interfaces\EmailTemplateServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 *
 */
class EmailTemplateService implements EmailTemplateServiceInterface
{

    /**
     * @param EmailTemplateRepositoryInterface $emailTemplateRepository
     * @param DocumentServiceInterface         $documentService
     */
    public function __construct(
        protected EmailTemplateRepositoryInterface $emailTemplateRepository,
        protected DocumentServiceInterface $documentService,
    )
    {
    }


    /**
     * @return LengthAwarePaginator
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function list()
    {
        $query = tenant()
            ->emailTemplates();

        $this->applySearchFilter($query);
        $this->applySortFilter($query);

        return
            $this->emailTemplateRepository->applyPaging($query);

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
            ->setTable(EmailTemplate::getTableName())
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
            ->setTable(EmailTemplate::getTableName())
            ->setQuery($query)
            ->setAllowedSorts(['name', 'title', 'description', 'data'])
            ->validate()
            ->applySort();
    }

    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getEmailTemplate(string $uid)
    {
        $emailTemplate = $this->emailTemplateRepository->findByUid($uid);
        if (!$emailTemplate) {
            throw new NotFoundException('Email template not found');
        }

        return $emailTemplate->fresh();
    }

    /**
     * @throws ServerErrorException
     * @throws BaseException
     */
    public function create(CreateEmailTemplateRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $emailTemplate = $this->emailTemplateRepository->create($data);

            if ($request->has('attachments')) {
                $attachments = $request->get('attachments');
                foreach ($attachments as $attachment) {
                    $document = $this->documentService->getDocument($attachment['uid'] ?? null);
                    $emailTemplate->addDocument($document, 'attachments');
                }
            }

            DB::commit();

        } catch (BaseException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create email template error');
        }

        return $emailTemplate->fresh();
    }

    /**
     * @throws ServerErrorException
     * @throws NotFoundException
     * @throws BaseException
     */
    public function update(string $uid, UpdateEmailTemplateRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $emailTemplate = $this->getEmailTemplate($uid);
            $emailTemplate = $this->emailTemplateRepository->update($data, $emailTemplate->id);
            $emailTemplate->clearDocumentCollection('attachments');

            if ($request->has('attachments')) {
                $attachments = $request->get('attachments');
                foreach ($attachments as $attachment) {
                    $document = $this->documentService->getDocument($attachment['uid'] ?? null);
                    $emailTemplate->addDocument($document, 'attachments');
                }
            }

            DB::commit();

        } catch (BaseException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new ServerErrorException('Oops! Create email template error');
        }

        return $emailTemplate;
    }

    /**
     * @param string $uid
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function delete(string $uid)
    {
        $emailTemplate = $this->getEmailTemplate($uid);
        $emailTemplate->clearDocumentCollection('attachments');
        return $emailTemplate->delete();
    }
}
