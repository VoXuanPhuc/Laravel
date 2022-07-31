<?php

namespace Encoda\Auth\Http\Middlewares;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Validation\UnauthorizedException;

class AuthMiddleware
{

    protected Auth $auth;

    public function __construct( Auth $auth )
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null ) {

        if( $this->auth->guard( $guard )->guest() ) {
            //throw new UnauthorizedException('Unauthorized', 401);
        }
        return $next($request);
    }
}
