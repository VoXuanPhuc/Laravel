<?php

namespace Encoda\AWSCognito\Exceptions;

use Throwable;

class InvalidJWKException extends \Exception
{

    public function __construct($message = "Invalid JWK", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
