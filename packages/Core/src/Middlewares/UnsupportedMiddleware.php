<?php
namespace Encoda\Core\Middlewares;

use Closure;
use Encoda\Core\Exceptions\UnsupportedMediaTypeException;
use Encoda\Core\Http\Header\Header;
use Encoda\Core\Http\Media\MediaType;
use Encoda\Core\Http\Method\RequestMethod;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class UnsupportedMiddleware extends BaseMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return Response|ResponseFactory|mixed
     * @throws UnsupportedMediaTypeException
     */
    public function handle( $request, Closure $next )
    {
        $type = $request->headers->get(Header::CONTENT_TYPE);

        if ( $request->getMethod() == RequestMethod::POST && stripos($type, MediaType::APPLICATION_JSON ) !== 0) {

            throw new UnsupportedMediaTypeException();
        }

        return $next($request);
    }
}
