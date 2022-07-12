<?php

namespace Encoda\Core\Exceptions;


class BadRequestException extends BaseException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = __('core::app.exception.bad_request') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
