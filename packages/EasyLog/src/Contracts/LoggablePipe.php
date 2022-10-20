<?php

namespace Encoda\EasyLog\Contracts;

use Closure;
use Encoda\EasyLog\Entities\EventLogBag;

interface LoggablePipe
{
    public function handle(EventLogBag $event, Closure $next): EventLogBag;
}
