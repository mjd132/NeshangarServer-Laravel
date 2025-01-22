<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Models\Client
 */
class Client extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Models\Client::class;
    }
}
