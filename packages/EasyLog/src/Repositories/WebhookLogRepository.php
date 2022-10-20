<?php

namespace Encoda\EasyLog\Repositories;

use Encoda\Core\Eloquent\Repository;
use Encoda\EasyLog\Models\WebhookLog;

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
