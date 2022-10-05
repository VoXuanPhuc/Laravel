<?php

namespace Encoda\Core\Traits;
/**
 *
 */
trait HasUID
{
    /**
     * @param             $query
     * @param string      $uid
     *
     * @return void
     */
    public function scopeHasUID($query, string $uid): void
    {
        $query->where('uid', $uid);
    }
}
