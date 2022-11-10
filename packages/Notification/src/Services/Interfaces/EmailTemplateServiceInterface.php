<?php

namespace Encoda\Notification\Services\Interfaces;

use Encoda\Notification\Http\Requests\EmailTemplate\CreateEmailTemplateRequest;
use Encoda\Notification\Http\Requests\EmailTemplate\UpdateEmailTemplateRequest;

/**
 *
 */
interface EmailTemplateServiceInterface
{

    /**
     * @return mixed
     */
    public function list();

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function getEmailTemplate(string $uid);

    /**
     * @param CreateEmailTemplateRequest $all
     *
     * @return mixed
     */
    public function create(CreateEmailTemplateRequest $all);

    /**
     * @param string                     $uid
     * @param UpdateEmailTemplateRequest $all
     *
     * @return mixed
     */
    public function update(string $uid, UpdateEmailTemplateRequest $all);

    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function delete(string $uid);

    /**
     * Return all without pagination
     * @return mixed
     */
    public function all();
}
