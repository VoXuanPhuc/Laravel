<?php

namespace Encoda\Core\Exceptions;

use App\Exceptions\Handler;
use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class ExceptionHandler extends Handler
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if( $request->wantsJson() || $request->isJson() ) {

            $statusCode = !in_array( $exception->getCode(), HttpStatusCode::STATUS_CODES ) ? HttpStatusCode::INTERNAL_SERVER_ERROR : $exception->getCode();
            $json = [
                'success' => false,
                'error' => [
                    'code' => $statusCode,
                    'message' => $exception->getMessage(),

                ],
            ];

            if ( env('APP_DEBUG' ) ) {
                $json['error']['trace'] =  $exception->getTraceAsString();
            }

            return response()->json($json, (int)$statusCode );
        }

        return parent::render($request, $exception);
    }
}
