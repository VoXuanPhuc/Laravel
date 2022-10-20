<?php

namespace Encoda\EasyLog\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Encoda\EasyLog\Contracts\EasyLog;

class InvalidConfiguration extends Exception
{
    /**
     * @param string $className
     * @return static
     */
    public static function modelIsNotValid(string $className): self
    {
        return new static("The given model class `{$className}` does not implement `".EasyLog::class.'` or it does not extend `'.Model::class.'`');
    }
}
