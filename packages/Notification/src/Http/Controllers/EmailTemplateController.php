<?php

namespace Encoda\Notification\Http\Controllers;

use Encoda\Notification\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use Encoda\Notification\Http\Requests\EmailTemplate\UpdateEmailTemplateRequest;
use Encoda\Notification\Services\Interfaces\EmailTemplateServiceInterface;

/**
 *
 */
class EmailTemplateController extends Controller
{
    /**
     * @param EmailTemplateServiceInterface $emailTemplateService
     */
    public function __construct(
        protected EmailTemplateServiceInterface $emailTemplateService
    )
    {
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->emailTemplateService->list();
    }

  /**
     * @return mixed
     */
    public function all()
    {
        return $this->emailTemplateService->all();
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function detail(string $uid)
    {
        return $this->emailTemplateService->getEmailTemplate($uid);
    }

    /**
     * @param CreateEmailTemplateRequest $request
     *
     * @return mixed
     */
    public function create(CreateEmailTemplateRequest $request)
    {
        return $this->emailTemplateService->create($request);
    }

    /**
     * @param UpdateEmailTemplateRequest $request
     * @param string                     $uid
     *
     * @return mixed
     */
    public function update(UpdateEmailTemplateRequest $request, string $uid)
    {
        return $this->emailTemplateService->update($uid, $request);
    }

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function delete(string $uid)
    {
        return $this->emailTemplateService->delete($uid);
    }
}
