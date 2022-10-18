<?php

namespace Encoda\Core\Helpers;

/**
 *
 */
class ObjectHelper
{
    /**
     * @param mixed $target
     *
     * @return \Illuminate\Support\Collection|mixed
     */
    public static function toCollection(mixed $target)
    {
        if (is_array($target)) {
            return collect($target);
        } elseif (!is_iterable($target)) {
            return collect([$target]);
        }
        return $target;
    }
}
