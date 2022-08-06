<?php

namespace Encoda\Auth\Exceptions;

use Encoda\Core\Exceptions\BaseException;
use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class UnauthorizedException extends BaseException
{

    public function __construct($message = HttpStatusCode::UNAUTHORIZED, $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'auth::app.login.unauthorized_exception';
        parent::__construct($message, $code, $previous);
    }
}
