<?php

namespace Encoda\Core\Exceptions;


use Encoda\Core\Http\HttpStatus\HttpStatusCode;

class BadRequestException extends BaseException
{

    public function __construct($message = "", $code = HttpStatusCode::BAD_REQUEST, Throwable $previous = null)
    {
        $message = $message ?: __('core::app.exception.bad_request') ;
        parent::__construct($message, $code, $previous);
    }
}
