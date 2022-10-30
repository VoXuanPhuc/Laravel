<?php

namespace Encoda\BCP\Http\Controllers;

use Encoda\BCP\Services\Interfaces\BCPLogServiceInterface;

/**
 *
 */
class BCPLogController extends Controller
{
    /**
     * @param BCPLogServiceInterface $logService
     */
    public function __construct(
        protected BCPLogServiceInterface $logService,
    )
    {
    }


    /**
     * @param string $uid
     *
     * @return mixed
     */
    public function logs(string $uid)
    {
        return $this->logService->getLogs($uid);
    }

}
