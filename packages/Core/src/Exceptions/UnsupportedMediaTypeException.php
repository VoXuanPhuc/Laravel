<?php

namespace Encoda\Core\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UnsupportedMediaTypeException extends BaseException
{

    public function __construct($message = "", $code = HttpStatusCode::UNSUPPORTED_MEDIA_TYPE, Throwable $previous = null)
    {
        $message = $message ?: __('core::app.exception.unsupported_media_type');
        parent::__construct($message, $code, $previous);
    }
}
