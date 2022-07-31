<?php

namespace Encoda\CORS\Middlewares;

use Closure;
use Encoda\CORS\Services\CORSService;
use Illuminate\Http\Request;

class CORSMiddleware
{

    /**
     * @var CorsService
     */
    private CORSService $service;


    /**
     * CorsMiddleware constructor.
     *
     * @param CORSService $service
     */
    public function __construct(CORSService $service)
    {
        $this->service = $service;
    }


    /**
     * Run the request filter.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->service->isCorsRequest($request)) {
            return $next($request);
        }

        if ($this->service->isPreflightRequest($request)) {
            return $this->service->handlePreflightRequest($request);
        }

        return $this->service->handleRequest($request, $next($request));
    }
}
