<?php

namespace Encoda\Core\Facades;

use Encoda\Core\AppContext\RequestContext;
use Illuminate\Support\Facades\Facade;

/**
 * @method static RequestContext request()
 */
class Context extends Facade
{
    /**
    * Get the registered name of the component
    *
    * @return string
    */
    protected static function getFacadeAccessor(): string
    {
        return 'context';
    }
}
