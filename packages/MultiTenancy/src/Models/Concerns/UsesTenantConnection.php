<?php

namespace Encoda\MultiTenancy\Models\Concerns;

use Encoda\MultiTenancy\Concerns\UsesMultitenancyConfig;

trait UsesTenantConnection
{
    use UsesMultitenancyConfig;

    public function getConnectionName()
    {
        return $this->tenantDatabaseConnectionName();
    }
}
