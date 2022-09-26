<?php

namespace Encoda\MultiTenancy\TenantFinder;

use Illuminate\Http\Request;
use Encoda\MultiTenancy\Models\Tenant;

abstract class TenantFinder
{
    abstract public function findForRequest(Request $request): ?Tenant;
}
