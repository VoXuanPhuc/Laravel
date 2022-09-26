<?php

namespace Encoda\MultiTenancy\Models\Concerns;

use Encoda\MultiTenancy\Concerns\UsesMultitenancyConfig;

trait UsesLandlordConnection
{
    use UsesMultitenancyConfig;

    public function getConnectionName()
    {
        return $this->landlordDatabaseConnectionName();
    }
}
