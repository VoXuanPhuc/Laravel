<?php

namespace Encoda\Log\Services;

use Encoda\Log\Repositories\WebhookLogRepository;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 *
 */
class WebhookLogService
{
    /**
     * @param WebhookLogRepository $webhookLogRepository
     */
    public function __construct(
        protected WebhookLogRepository $webhookLogRepository
    )
    {
    }

    /**
     * @param array $data
     *
     * @return void
     * @throws ValidatorException
     */
    public function log(array $data)
    {
        $this->webhookLogRepository->create($data);
    }
}
