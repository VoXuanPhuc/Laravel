<?php

namespace Encoda\Dashboard\Http\Controllers\Tenant;

use Encoda\Dashboard\Http\Controllers\Controller;
use Encoda\Dashboard\Services\Interfaces\TenantStatisticServiceInterface;

class TenantStatisticController extends Controller
{
    public function __construct( protected TenantStatisticServiceInterface $statisticService )
    {
    }

    public function systemStatistics() {

        return $this->statisticService->getSystemStatistics();
    }
}
