<?php

namespace Encoda\MultiTenancy\TenantFinder;

use Illuminate\Http\Request;
use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;
use Encoda\MultiTenancy\Models\Tenant;

class DomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        return $this->getTenantModel()::whereDomain($host)->first();
    }
}
