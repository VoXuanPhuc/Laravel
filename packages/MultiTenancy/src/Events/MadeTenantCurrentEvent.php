<?php

namespace Encoda\MultiTenancy\Events;

use Encoda\MultiTenancy\Models\Tenant;

class MadeTenantCurrentEvent
{
    public function __construct(
        public Tenant $tenant
    ) {
    }
}
