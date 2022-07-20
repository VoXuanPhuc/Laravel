<?php

namespace Encoda\Core\Middlewares;

use Closure;
use Encoda\Core\Exceptions\BadRequestException;
use Encoda\Core\Http\Method\RequestMethod;

class JsonPayloadHandlerMiddleware extends BaseMiddleware
{

    const PARSED_METHODS = [

        RequestMethod::POST,
        RequestMethod::PUT,
        RequestMethod::PATCH,
    ];

    /**
     * @param \Laravel\Lumen\Http\Request $request
     * @param Closure $next
     * @return mixed
     * @throws BadRequestException
     */
    public function handle($request, Closure $next) {

        if (
            $request->isJson()
            && in_array( RequestMethod::from($request->getMethod()), self::PARSED_METHODS)
        ) {

            $jsonData = json_decode($request->getContent(), true );

            if( json_last_error() != JSON_ERROR_NONE ) {
                throw new BadRequestException( 'Invalid JSON data' );
            }

            $request->merge((array)$jsonData);
        }

        return $next($request);
    }


}
