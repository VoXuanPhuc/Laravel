<?php

namespace Encoda\Dashboard\Http\Controllers\Admin;

use Encoda\Dashboard\Http\Controllers\Controller;
use Encoda\Dashboard\Services\Interfaces\SystemStatisticServiceInterface;

class SystemStatisticController extends Controller
{
    public function __construct( protected SystemStatisticServiceInterface $statisticService )
    {
    }

    public function systemStatistics() {

        return $this->statisticService->getSystemStatistics();
    }
}
