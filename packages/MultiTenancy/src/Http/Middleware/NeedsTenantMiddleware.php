<?php

namespace Encoda\MultiTenancy\Http\Middleware;

use Closure;
use Encoda\MultiTenancy\Exceptions\NoCurrentTenant;
use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;

class NeedsTenantMiddleware
{
    use UsesTenantModel;

    public function handle($request, Closure $next)
    {
        if (! $this->getTenantModel()::checkCurrent()) {
            return $this->handleInvalidRequest();
        }

        return $next($request);
    }

    public function handleInvalidRequest()
    {
        throw NoCurrentTenant::make();
    }
}
