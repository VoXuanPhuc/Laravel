<?php

namespace Encoda\Auth\Exceptions;

use Encoda\Core\Exceptions\BaseException;
use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class UnauthorizedException extends BaseException
{

    public function __construct($message = '', $code = HttpStatusCode::UNAUTHORIZED, Throwable $previous = null)
    {
        $message = $message ?: __('auth::app.login.unauthorized_exception');
        parent::__construct($message, $code, $previous);
    }
}
