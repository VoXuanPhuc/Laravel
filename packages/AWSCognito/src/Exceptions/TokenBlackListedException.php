<?php

namespace Encoda\AWSCognito\Exceptions;

use Exception;
use Throwable;

class TokenBlackListedException extends Exception
{
    public function __construct($message = "Token has been blacklisted", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
