<?php

namespace Encoda\AWSCognito\Exceptions;

use Exception;
use Throwable;

class MissingTokenException extends Exception
{
    public function __construct( $message = "Missing token", $code = 401, Throwable $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }

}
