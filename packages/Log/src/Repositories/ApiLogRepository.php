<?php

namespace Encoda\Log\Repositories;

use Encoda\Log\Models\APILog;
use Webkul\Core\Eloquent\Repository;

/**
 *
 */
class ApiLogRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return APILog::class;
    }
}
