<?php

namespace Encoda\BIA\Http\Controllers;

use Encoda\BIA\Services\Interfaces\BIALogServiceInterface;

/**
 *
 */
class BIALogController extends Controller
{
    /**
     * @param BIALogServiceInterface $logService
     */
    public function __construct(
        protected BIALogServiceInterface $logService,
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
