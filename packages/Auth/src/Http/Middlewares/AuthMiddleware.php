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

    public function handle($request, Closure $next ) {

        if( !$this->auth->guard()->user() ) {
            throw new UnauthorizedException('Unauthorized', 401 );
        }
        return $next($request);
    }
}
