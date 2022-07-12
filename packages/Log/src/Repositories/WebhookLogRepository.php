<?php

namespace Encoda\Log\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\Log\Models\WebhookLog;

class WebhookLogRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return WebhookLog::class;
    }
}
