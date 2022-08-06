<?php

namespace Encoda\Auth\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;
use Throwable;

class UserNotFoundException extends \Encoda\Core\Exceptions\BaseException
{

    public function __construct($message = "", $code = HttpStatusCode::BAD_REQUEST, Throwable $previous = null)
    {
        $message = $message ?: __( 'auth::app.login.incorrect_user_or_password' );
        parent::__construct($message, $code, $previous);
    }
}
