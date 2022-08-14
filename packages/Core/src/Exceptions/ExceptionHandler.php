<?php

namespace Encoda\Core\Exceptions;

use App\Exceptions\Handler;
use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        if( $request->wantsJson() || $request->acceptsJson() || $request->isJson() ) {

            $statusCode = !in_array( $exception->getCode(), HttpStatusCode::STATUS_CODES ) ? HttpStatusCode::INTERNAL_SERVER_ERROR : $exception->getCode();

            if( property_exists( $exception, 'status') && in_array( $exception->status, HttpStatusCode::STATUS_CODES  ) ) {
                $statusCode = $exception->status;
            }
            //Get the original http code from exception if exists
            if( $exception instanceof  HttpException ) {
                /** @var HttpException $exception */
                $statusCode = $exception->getStatusCode();
            }

            $json = [
                'status' => $statusCode,
                'success' => false,
                'error' => [
                    'code' => $statusCode,
                    'message' => $exception->getMessage(),

                ],
            ];

            if ( env('APP_DEBUG' ) ) {
                $json['error']['trace'] =  $exception->getTraceAsString();
            }

            return response()->json($json, $statusCode);
        }

        return parent::render($request, $exception);
    }
}
