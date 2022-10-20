<?php

namespace Encoda\EasyLog\Repositories;

use Encoda\Log\Models\APILog;

/**
 *
 */
class ApiLogRepository extends \Encoda\Core\Eloquent\Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return APILog::class;
    }
}
