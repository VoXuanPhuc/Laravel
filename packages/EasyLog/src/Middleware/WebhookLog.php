<?php

namespace Encoda\EasyLog\Middleware;

use Encoda\EasyLog\Services\WebhookLogService;
use Closure;
use Illuminate\Http\Request;

/**
 *
 */
class WebhookLog
{
    /**
     * @param WebhookLogService $webhookLogService
     */
    public function __construct(
        protected WebhookLogService $webhookLogService
    )
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function handle(Request $request, Closure $next)
    {
        $data = [
            'url' => $request->url(),
            'request_header' => json_encode($request->headers, JSON_UNESCAPED_UNICODE ),
            'request_param' => json_encode($request->all(), JSON_UNESCAPED_UNICODE ),
        ];
        $this->webhookLogService->log($data);

        return $next($request);
    }
}
