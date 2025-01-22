<?php

namespace App\Events\WebSocket;

use Illuminate\Foundation\Events\Dispatchable;
use WebSocket\Connection;

class OnMessageSent
{
    use Dispatchable;

    public function __construct(public string $message, public Connection|null $client = null)
    {
    }
}
