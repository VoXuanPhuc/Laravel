<?php

namespace Encoda\MultiTenancy\Tasks;

use Encoda\MultiTenancy\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
