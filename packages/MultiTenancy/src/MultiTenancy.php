<?php

namespace Encoda\MultiTenancy;

use Encoda\MultiTenancy\Actions\MakeQueueTenantAwareAction;
use Encoda\MultiTenancy\Concerns\UsesMultitenancyConfig;
use Encoda\MultiTenancy\Models\Concerns\UsesTenantModel;
use Encoda\MultiTenancy\Models\Tenant;
use Encoda\MultiTenancy\Tasks\TasksCollection;
use Encoda\MultiTenancy\TenantFinder\TenantFinder;
use Laravel\Lumen\Application;

class MultiTenancy
{
    use UsesTenantModel;
    use UsesMultitenancyConfig;

    public function __construct(public Application  $app)
    {
    }

    public function start(): void
    {
        $this
            ->registerTenantFinder()
            ->registerTasksCollection()
            ->configureRequests()
            ->configureQueue();
        }

    public function end(): void
    {
        Tenant::forgetCurrent();
    }

    protected function determineCurrentTenant(): void
    {
        if (! $this->app['config']->get('multitenancy.tenant_finder')) {
            return;
        }

        /** @var \Encoda\Multitenancy\TenantFinder\TenantFinder $tenantFinder */
        $tenantFinder = $this->app[TenantFinder::class];

        $tenant = $tenantFinder->findForRequest($this->app['request']);

        $tenant?->makeCurrent();
    }

    protected function registerTasksCollection(): self
    {
        $this->app->singleton(TasksCollection::class, function () {
            $taskClassNames = $this->app['config']->get('multitenancy.switch_tenant_tasks');

            return new TasksCollection($taskClassNames);
        });

        return $this;
    }

    protected function registerTenantFinder(): self
    {
        if ($this->app['config']->get('multitenancy.tenant_finder')) {
            $this->app->bind(TenantFinder::class, $this->app['config']->get('multitenancy.tenant_finder'));
        }

        return $this;
    }

    protected function configureRequests(): self
    {
        if (! $this->app->runningInConsole()) {
            $this->determineCurrentTenant();
        }

        return $this;
    }

    protected function configureQueue(): self
    {
        $this
            ->getMultitenancyActionClass(
                actionName: 'make_queue_tenant_aware_action',
                actionClass: MakeQueueTenantAwareAction::class
            )
            ->execute();

        return $this;
    }
}
