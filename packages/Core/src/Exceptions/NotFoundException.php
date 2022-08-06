<?php

namespace Encoda\Core\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class NotFoundException extends BaseException
{

    public function __construct($message = "", $code = HttpStatusCode::NOT_FOUND, Throwable $previous = null)
    {
        $message = __('core::app.exception.not_found') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
