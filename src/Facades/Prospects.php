<?php

namespace Homeful\Prospects\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\Prospects\Prospects
 */
class Prospects extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\Prospects\Prospects::class;
    }
}
