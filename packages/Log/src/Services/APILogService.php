<?php

namespace Amazbin\Log\Services;

use Amazbin\Log\Repositories\ApiLogRepository;

/**
 *
 */
class APILogService
{
    /**
     * @param ApiLogRepository $apiLogRepository
     */
    public function __construct(
        protected ApiLogRepository $apiLogRepository
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
        $this->apiLogRepository->create($data);
    }
}