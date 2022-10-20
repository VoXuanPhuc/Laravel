<?php

namespace Encoda\EasyLog\Facades;

use Illuminate\Support\Facades\Facade;
use Encoda\EasyLog\Entities\LogBatch as EasyLogBatch;

/**
 * @method static string getUuid()
 * @method static mixed withinBatch(\Closure $callback)
 * @method static void startBatch()
 * @method static void setBatch(string $uuid): void
 * @method static bool isOpen()
 * @method static void endBatch()
 *
 * @see \Encoda\EasyLog\LogBatch
 */
class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EasyLogBatch::class;
    }
}
