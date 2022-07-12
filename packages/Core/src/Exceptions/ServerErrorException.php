<?php

namespace Encoda\Core\Exceptions;

class ServerErrorException extends BaseException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = __('core::app.exception.server_errors') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
