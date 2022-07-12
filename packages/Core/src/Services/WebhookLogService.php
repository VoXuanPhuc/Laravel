<?php

namespace Encoda\Log\Services;

use Encoda\Log\Repositories\WebhookLogRepository;

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
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function log(array $data)
    {
        $this->webhookLogRepository->create($data);
    }
}
