<?php

namespace App\Listeners\WebSocket;

use App\Events\WebSocket\OnHandshake;
use App\Services\ClientService;

class OnHandshakeListener
{
    public function __construct(protected ClientService $clientService)
    {
    }

    public function handle(OnHandshake $event): void
    {
        $this->clientService->registerClientConnection($event->connection, $event->request);
    }
}
