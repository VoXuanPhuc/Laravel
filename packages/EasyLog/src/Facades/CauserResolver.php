<?php

namespace Encoda\EasyLog\Facades;

use Encoda\Core\Models\Model;
use Illuminate\Support\Facades\Facade;
use Encoda\EasyLog\Resolvers\CauserResolver as EasyLogCauserResolver;

/**
 * @method static Model|null resolve(\Illuminate\Database\Eloquent\Model|int|string|null $subject = null)
 * @method static EasyLogCauserResolver resolveUsing(\Closure $callback)
 * @method static EasyLogCauserResolver setCauser(\Illuminate\Database\Eloquent\Model|null $causer)
 *
 * @see \Encoda\EasyLog\CauserResolver
 */
class CauserResolver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EasyLogCauserResolver::class;
    }
}
