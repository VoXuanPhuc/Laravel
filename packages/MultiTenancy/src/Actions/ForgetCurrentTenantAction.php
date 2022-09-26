<?php

namespace Encoda\MultiTenancy\Actions;

use Encoda\MultiTenancy\Events\ForgettingCurrentTenantEvent;
use Encoda\MultiTenancy\Events\ForgotCurrentTenantEvent;
use Encoda\MultiTenancy\Models\Tenant;
use Encoda\MultiTenancy\Tasks\SwitchTenantTask;
use Encoda\MultiTenancy\Tasks\TasksCollection;

class ForgetCurrentTenantAction
{
    public function __construct(
        protected TasksCollection $tasksCollection
    ) {
    }

    public function execute(Tenant $tenant)
    {
        event(new ForgettingCurrentTenantEvent($tenant));

        $this
            ->performTaskToForgetCurrentTenant()
            ->clearBoundCurrentTenant();

        event(new ForgotCurrentTenantEvent($tenant));
    }

    protected function performTaskToForgetCurrentTenant(): self
    {
        $this->tasksCollection->each(fn (SwitchTenantTask $task) => $task->forgetCurrent());

        return $this;
    }

    protected function clearBoundCurrentTenant()
    {
        $containerKey = config('multitenancy.current_tenant_container_key');

        app()->forgetInstance($containerKey);
    }
}
