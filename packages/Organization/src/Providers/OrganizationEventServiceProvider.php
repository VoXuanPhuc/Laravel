<?php

namespace Encoda\Organization\Providers;

use Encoda\Organization\Listeners\CreateOrganization;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrganizationEventServiceProvider extends ServiceProvider
{

    public function boot() {

        Event::listen('organization.create.after', CreateOrganization::class . '@handle' );
    }

}
