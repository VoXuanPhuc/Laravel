<?php

namespace Encoda\MultiTenancy\Events;

use Encoda\MultiTenancy\Models\Tenant;

class ForgotCurrentTenantEvent
{
    public function __construct(
        public Tenant $tenant
    ) {
    }
}
