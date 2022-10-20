<?php

namespace Encoda\EasyLog\Exceptions;

use Exception;

class CouldNotLogAction extends Exception
{
    /**
     * @param $id
     * @return static
     */
    public static function couldNotDetermineUser($id): self
    {
        return new static("Could not determine a user with identifier `{$id}`.");
    }
}
