<?php

namespace App\Listeners\User;

use App\Events\User\UserStatusChanged;
use App\Services\ClientService;

class UserStatusChangedListener
{
    public function __construct(protected ClientService $clientService)
    {
    }

    public function handle(UserStatusChanged $event): void
    {
        $this->clientService->sendUsersListToClients();
    }
}
