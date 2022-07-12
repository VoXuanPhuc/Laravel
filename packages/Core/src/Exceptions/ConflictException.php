<?php
namespace Encoda\Core\Exceptions;

class ConflictException extends BaseException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = __('core::app.exception.conflict') ?? $message ;
        parent::__construct($message, $code, $previous);
    }
}
