<?php

namespace Encoda\MultiTenancy;

use Encoda\MultiTenancy\Models\Tenant;

class Landlord
{
    public static function execute(callable $callable)
    {
        $originalCurrentTenant = Tenant::current();

        Tenant::forgetCurrent();

        $result = $callable();

        $originalCurrentTenant?->makeCurrent();

        return $result;
    }
}