<?php

namespace Encoda\Core\Middlewares;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Laravel\Lumen\Http\Request;
use function Sodium\add;

class ResponseHandlerMiddleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed|void
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        return $response;
    }
}
