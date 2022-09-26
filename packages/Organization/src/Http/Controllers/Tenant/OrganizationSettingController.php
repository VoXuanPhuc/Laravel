<?php

namespace Encoda\Organization\Http\Controllers\Tenant;

use Encoda\Organization\Services\Interfaces\OrganizationSettingServiceInterface;

class OrganizationSettingController
{

    public function __construct(
        protected OrganizationSettingServiceInterface $orgSettingService
    )
    {
    }

    public function getConfigsByDomain() {

        return $this->orgSettingService->buildConfigs();
    }

}
