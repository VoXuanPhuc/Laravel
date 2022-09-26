<?php

namespace Encoda\MultiTenancy\Models\Concerns;

use Encoda\MultiTenancy\Models\Tenant;

trait UsesTenantModel
{
    public function getTenantModel(): Tenant
    {
        $tenantModelClass = config('multitenancy.tenant_model');

        return new $tenantModelClass();
    }
}
