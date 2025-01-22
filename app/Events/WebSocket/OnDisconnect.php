<?php

namespace App\Events\WebSocket;

use Illuminate\Foundation\Events\Dispatchable;
use WebSocket\Connection;

class OnDisconnect
{
    use Dispatchable;

    public function __construct(public Connection $connection)
    {
    }
}
