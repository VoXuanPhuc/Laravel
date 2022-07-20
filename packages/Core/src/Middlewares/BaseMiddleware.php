<?php
namespace Encoda\Core\Middlewares;

use Closure;
use Laravel\Lumen\Http\Request;

abstract class BaseMiddleware
{

    /**
     * @param \Laravel\Lumen\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    abstract function handle( $request, Closure $next );
}
