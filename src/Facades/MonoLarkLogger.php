<?php

namespace Drayeasy\MonoLarkLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Drayeasy\MonoLarkLogger\MonoLarkLogger
 */
class MonoLarkLogger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Drayeasy\MonoLarkLogger\MonoLarkLogger::class;
    }
}
