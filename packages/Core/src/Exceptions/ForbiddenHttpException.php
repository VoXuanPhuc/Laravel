<?php

namespace Encoda\Core\Exceptions;

use Encoda\Core\Http\HttpStatus\HttpStatusCode;

/**
 * Exception thrown if user doesn't have permission on resources
 */
class ForbiddenHttpException extends BaseException
{
    /**
     * @param string|null $message Code or message exception message
     * @param string|null $errorCode
     * @param array $replacements To replace tokens in error message
     * @param array $extras Extra info to handle in frontend
     */
    public function __construct(string $message = "", string $errorCode = HttpStatusCode::FORBIDDEN, Throwable $previous = null)
    {
        parent::__construct($message, $errorCode, $previous);
    }
}
