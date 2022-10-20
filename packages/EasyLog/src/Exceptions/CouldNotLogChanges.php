<?php

namespace Encoda\EasyLog\Exceptions;

use Exception;

class CouldNotLogChanges extends Exception
{
    /**
     * @param $attribute
     * @return static
     */
    public static function invalidAttribute($attribute): self
    {
        return new static("Cannot log attribute `{$attribute}`. Can only log attributes of a model or a directly related model.");
    }
}
