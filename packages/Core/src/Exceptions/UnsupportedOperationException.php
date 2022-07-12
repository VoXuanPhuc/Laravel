<?php

namespace Encoda\Core\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class UnsupportedOperationException extends BaseException
{

    /**
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = HttpStatusCode::METHOD_NOT_ALLOWED, Throwable $previous = null)
    {
        $message = __('core::app.exception.unsupported_operation') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
