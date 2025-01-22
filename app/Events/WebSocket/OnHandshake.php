<?php

namespace App\Events\WebSocket;

use Illuminate\Foundation\Events\Dispatchable;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use WebSocket\Connection;

class OnHandshake
{
    use Dispatchable;

    public function __construct(public Connection $connection, public RequestInterface $request, public ResponseInterface $response)
    {
    }
}
