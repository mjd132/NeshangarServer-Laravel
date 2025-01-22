<?php

namespace App\Listeners\User;

use App\Events\User\UserConnected;
use App\Services\ClientService;

class UserConnectedListener
{
    public function __construct(protected ClientService $clientService)
    {
    }

    public function handle(UserConnected $event): void
    {
        $this->clientService->sendUsersListToClients();
    }
}
