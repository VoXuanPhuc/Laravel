<?php

namespace Encoda\AWSCognito\Exceptions;

use Exception;
use Throwable;

class InvalidTokenStructureException extends Exception
{

    public function __construct(
        $message = "Missing or not enough token structures",
        $code = 401,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
