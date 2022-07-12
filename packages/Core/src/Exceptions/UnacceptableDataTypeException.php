<?php

namespace Encoda\Core\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class UnacceptableDataTypeException extends BaseException
{

    /**
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = HttpStatusCode::NOT_ACCEPTABLE, Throwable $previous = null)
    {
        $message = __('core::app.exception.unacceptable_data_type') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
