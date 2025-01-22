<?php

namespace App\Events\WebSocket;

use Illuminate\Foundation\Events\Dispatchable;
use WebSocket\Connection;
use WebSocket\Message\Text;

class OnMessageReceived
{
    use Dispatchable;

    public function __construct(public Text $message, public Connection $connection)
    {
    }
}
