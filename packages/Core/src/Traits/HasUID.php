<?php

namespace Encoda\Core\Traits;
/**
 *
 *  @method hasUID($query)
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
