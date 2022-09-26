<?php

namespace Encoda\MultiTenancy\Events;

use Encoda\MultiTenancy\Models\Tenant;

class MakingTenantCurrentEvent
{
    public function __construct(
        public Tenant $tenant
    ) {
    }
}
