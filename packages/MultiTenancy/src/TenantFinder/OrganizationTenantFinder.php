<?php

namespace Encoda\MultiTenancy\TenantFinder;

use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;
use Encoda\MultiTenancy\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class OrganizationTenantFinder extends TenantFinder
{
    use UsesTenantModel,ProvidesConvenienceMethods;

    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        return $this->getTenantModel()::whereDomain($host)->first();
    }
}
