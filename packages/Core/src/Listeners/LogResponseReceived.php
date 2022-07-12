<?php
namespace Encoda\Core\Listeners;

use Encoda\Log\Services\APILogService;
use Illuminate\Http\Client\Events\ResponseReceived;

class LogResponseReceived
{

    /**
     * @param APILogService $APILogService
     */
    public function __construct(
        protected APILogService $APILogService
    )
    {

    }

    /**
     * Handle the event.
     *
     * @param ResponseReceived $event
     * @return void
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function handle(ResponseReceived $event)
    {
        $data = [
            'url' => $event->request->url(),
            'request_header' => json_encode($event->request->headers()),
            'request_param' => json_encode($event->request->data()),
            'response_header' => json_encode($event->response->headers()),
            'response_body' => ($event->response->body()),
            'status' => ($event->response->status()),
        ];
        $this->APILogService->log($data);
    }
}
