<?php

namespace Encoda\MultiTenancy\Events;

use Encoda\MultiTenancy\Models\Tenant;

class ForgettingCurrentTenantEvent
{
    public function __construct(
        public Tenant $tenant
    ) {
    }
}
