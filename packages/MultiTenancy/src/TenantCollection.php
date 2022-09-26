<?php

namespace Encoda\MultiTenancy;

use Illuminate\Database\Eloquent\Collection;
use Encoda\MultiTenancy\Models\Tenant;

class TenantCollection extends Collection
{
    public function eachCurrent(callable $callable): self
    {
        return $this->performCollectionMethodWhileMakingTenantsCurrent(
            operation: 'each',
            callable: $callable
        );
    }

    public function filterCurrent(callable $callable): self
    {
        return $this->performCollectionMethodWhileMakingTenantsCurrent(
            operation: 'filter',
            callable: $callable
        );
    }

    public function mapCurrent(callable $callable): self
    {
        return $this->performCollectionMethodWhileMakingTenantsCurrent(
            operation: 'map',
            callable: $callable
        );
    }

    public function rejectCurrent(callable $callable): self
    {
        return $this->performCollectionMethodWhileMakingTenantsCurrent(
            operation: 'reject',
            callable: $callable
        );
    }

    protected function performCollectionMethodWhileMakingTenantsCurrent(string $operation, callable $callable): self
    {
        $collection = $this->$operation(fn (Tenant $tenant) => $tenant->execute($callable));

        return new static($collection->items);
    }
}
