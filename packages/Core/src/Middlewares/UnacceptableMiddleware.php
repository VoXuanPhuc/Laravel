<?php
namespace Encoda\Core\Middlewares;

use Closure;
use Encoda\Core\Exceptions\UnacceptableDataTypeException;
use Encoda\Core\Http\Header\Header;
use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Encoda\Core\Http\Media\MediaType;
use Illuminate\Http\Response;
use Laravel\Lumen\Http\ResponseFactory;

class UnacceptableMiddleware extends BaseMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return Response|ResponseFactory|mixed
     */
    public function handle( $request, Closure $next )
    {
        $accept = $request->headers->get(Header::ACCEPT );

        if (
            $accept
            && stripos($accept, MediaType::WILDCARD ) === false
            && stripos($accept, MediaType::JSON ) === false
        ) {
            throw new UnacceptableDataTypeException();
        }

        return $next($request);
    }
}
