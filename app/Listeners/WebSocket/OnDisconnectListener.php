<?php

namespace App\Listeners\WebSocket;

use App\Events\WebSocket\OnDisconnect;
use App\Services\ClientService;

class OnDisconnectListener
{
    public function __construct(protected ClientService $clientService)
    {
    }

    public function handle(OnDisconnect $event): void
    {
        $this->clientService->disconnectClient($event->connection);
    }
}
